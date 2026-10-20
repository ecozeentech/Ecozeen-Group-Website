@include('errors.minimal', [
    'code' => '422',
    'title' => 'Invalid Request',
    'message' => 'The submitted data could not be processed. Please check your input and try again.',
])
