<?php

namespace Tests\Feature\Site;

use App\Models\JobApplication;
use App\Models\JobListing;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CareerApplicationTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_careers_index_and_an_open_listing_detail_page_are_reachable(): void
    {
        $listing = JobListing::factory()->create(['title' => 'Software Engineer']);

        $this->get('/careers')->assertOk();
        $this->get("/careers/{$listing->slug}")->assertOk()->assertSee('Software Engineer');
    }

    public function test_a_closed_listing_detail_page_remains_viewable_but_cannot_be_applied_to(): void
    {
        $listing = JobListing::factory()->closed()->create();

        $this->get("/careers/{$listing->slug}")->assertOk();

        $this->post("/careers/{$listing->slug}/apply", [
            'full_name' => 'Jane Applicant',
            'email' => 'jane@example.com',
            'consent' => '1',
            'website' => '',
            'resume' => UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf'),
        ])->assertNotFound();
    }

    public function test_a_valid_application_with_a_pdf_resume_is_stored_on_the_private_disk(): void
    {
        Storage::fake('local');
        Mail::fake();

        $listing = JobListing::factory()->create();

        $response = $this->post("/careers/{$listing->slug}/apply", [
            'full_name' => 'Jane Applicant',
            'email' => 'jane@example.com',
            'phone' => '0000000000',
            'cover_letter' => 'I would love to join Ecozeen Group.',
            'consent' => '1',
            'website' => '',
            'resume' => UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf'),
        ]);

        $response->assertRedirect()->assertSessionHas('status');

        $application = JobApplication::where('email', 'jane@example.com')->first();
        $this->assertNotNull($application);
        $this->assertNotNull($application->resume_media_id);
        Storage::disk('local')->assertExists($application->resume->path);
    }

    public function test_a_non_pdf_resume_upload_is_rejected(): void
    {
        $listing = JobListing::factory()->create();

        $this->post("/careers/{$listing->slug}/apply", [
            'full_name' => 'Jane Applicant',
            'email' => 'jane@example.com',
            'consent' => '1',
            'website' => '',
            'resume' => UploadedFile::fake()->create('resume.exe', 100, 'application/x-msdownload'),
        ])->assertSessionHasErrors('resume');

        $this->assertDatabaseMissing('job_applications', ['email' => 'jane@example.com']);
    }

    public function test_an_application_without_consent_is_rejected(): void
    {
        $listing = JobListing::factory()->create();

        $this->post("/careers/{$listing->slug}/apply", [
            'full_name' => 'Jane Applicant',
            'email' => 'jane@example.com',
            'consent' => null,
            'website' => '',
            'resume' => UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf'),
        ])->assertSessionHasErrors('consent');
    }

    public function test_the_honeypot_field_rejects_automated_applications(): void
    {
        $listing = JobListing::factory()->create();

        $this->post("/careers/{$listing->slug}/apply", [
            'full_name' => 'Spam Bot',
            'email' => 'bot@example.com',
            'consent' => '1',
            'website' => 'http://spambot.example',
            'resume' => UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf'),
        ])->assertSessionHasErrors('website');

        $this->assertDatabaseMissing('job_applications', ['email' => 'bot@example.com']);
    }

    public function test_the_resume_is_not_publicly_downloadable_but_an_authenticated_admin_can_download_it(): void
    {
        Storage::fake('local');
        Mail::fake();

        $listing = JobListing::factory()->create();

        $this->post("/careers/{$listing->slug}/apply", [
            'full_name' => 'Jane Applicant',
            'email' => 'jane@example.com',
            'consent' => '1',
            'website' => '',
            'resume' => UploadedFile::fake()->create('resume.pdf', 100, 'application/pdf'),
        ]);

        $application = JobApplication::where('email', 'jane@example.com')->first();

        $this->get("/admin/job-applications/{$application->id}/resume")
            ->assertRedirect('/login');

        $this->seed(RolesAndPermissionsSeeder::class);
        $admin = User::factory()->create();
        $admin->assignRole('Super Administrator');

        $this->actingAs($admin)
            ->get("/admin/job-applications/{$application->id}/resume")
            ->assertOk();
    }
}
