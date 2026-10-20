@component('mail::message')
# Subscription Confirmed

Thanks for subscribing to Ecozeen Group's newsletter, {{ $subscriber->name ?: 'there' }}.

You will receive occasional updates about our companies, products, and services.

@component('mail::button', ['url' => route('newsletter.unsubscribe', $subscriber->unsubscribe_token)])
Unsubscribe anytime
@endcomponent

Thanks,<br>
Ecozeen Group
@endcomponent
