@props([
    'heading' => 'Looking to hire?',
    'subheading' => 'Post your job listing now and find the perfect candidate.',
])

<section class="container mx-auto my-6">
    <div
        class="flex items-center justify-between rounded bg-blue-800 p-4 text-white">
        <div>
            <h2 class="text-xl font-semibold">{{ $heading }}</h2>
            <p class="mt-2 text-lg text-gray-200">
                {{ $subheading }}
            </p>
        </div>
       <x-button-link url="/jobs/create" icon="edit">Create Job</x-button-link>
    </div>
</section>
