<form method="POST" action="{{ route('logout') }}" class="block px-4 py-2 hover:bg-blue-700 cursor-pointer">
    @csrf
    <button type="submit" class="text-white">
        <i class="fa fa-sign-out"></i> Logout
    </button>
</form>