<header class="bg-blue-900 p-4 text-white" x-data="{open: false}">
    <div class="container mx-auto flex items-center justify-between">
        <h1 class="text-3xl font-semibold">
            <a href="{{ route('home') }}">Workopia</a>
        </h1>
        <nav class="hidden items-center space-x-4 md:flex">
            <x-nav-link url="/" :active="request()->is('/')">Home</x-nav-link>
            <x-nav-link url="/jobs" :active="request()->is('jobs')">All Jobs</x-nav-link>
            <x-nav-link url="/jobs/saved" :active="request()->is('jobs/saved')">Saved
                Jobs</x-nav-link>
            <x-nav-link url="/login" :active="request()->is('login')"
                icon="user">Login</x-nav-link>
            <x-nav-link url="/register" :active="request()->is('register')">Register</x-nav-link>
            <x-nav-link url="/dashboard" :active="request()->is('dashboard')" icon="gauge">
                Dashboard
            </x-nav-link>
            <x-button-link url="/jobs/create" icon='edit'>Create
                Job</x-button-link>
        </nav>

        <button @click="open = !open" class="text-white md:hidden flex items-center cursor-pointer">
            <i class="fa fa-bars text-2xl"></i>
        </button>
    </div>
    <!-- Mobile Menu -->
    <div
        x-show="open"
        @click.away="open = false"
        id="mobile-menu"
        class="mt-5 space-y-2 bg-blue-900 pb-4 text-white md:hidden">
        <x-nav-link url="/jobs" :active="request()->is('jobs')" :mobile="true">All
            Jobs</x-nav-link>
        <x-nav-link url="/jobs/saved" :active="request()->is('jobs/saved')" :mobile="true">Saved
            Jobs</x-nav-link>
        <x-nav-link url="/login" :active="request()->is('login')"
            :mobile="true">Login</x-nav-link>
        <x-nav-link url="/register" :active="request()->is('register')"
            :mobile="true">Register</x-nav-link>
        <x-nav-link url="/dashboard" :active="request()->is('dashboard')"
            :mobile="true">Dashboard</x-nav-link>
        <x-button-link url='/jobs/create' icon='edit' :block='true'>Create Job</x-button-link>
    </div>
</header>
