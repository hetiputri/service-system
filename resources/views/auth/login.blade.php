@extends('layouts.auth')

@section('title', 'Login SIMPEG')

@section('content')
<div class="
    bg-white rounded-xl shadow-2xl overflow-hidden
    w-full max-w-md md:max-w-4xl 
    grid grid-cols-1 md:grid-cols-2
    relative
    mx-4 md:mx-auto
">

{{-- WATERMARK --}}
    <img src="{{ asset('image/auth/logoTerpotong.png') }}"  class="absolute hidden md:block  bottom-3  w-12 md:w-16 opacity-80 pointer-events-none select-none z-0">
    {{-- LEFT : FORM --}}
    <div class="p-6 md:p-10 ">
        <div class="mb-8 text-center">
            <img src="{{ asset('image/LogoSimpeg1.png') }}" alt="Logo" class="h-14 md:h-16 mx-auto mb-4">
            <!-- <h2 class="text-2xl font-bold">SIMPEG</h2>
            <p class="text-gray-500 text-sm">
                Sistem Informasi Manajemen Kepegawaian
            </p> -->
        </div>

        <form method="POST" action="{{ route('loginPost') }}" class="space-y-4" id="loginForm">
        @csrf

        {{-- NIP --}}
        <div class="relative">

            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                {{-- icon user --}}
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5 text-gray-400"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M15.75 6.75a3.75 3.75 0 11-7.5 0
                            3.75 3.75 0 017.5 0z" />

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4.5 20.25a8.25 8.25 0 0115 0" />

                </svg>

            </div>

            <input
                type="text"
                name="nip" inputmode="numeric"
                placeholder="NIP"
                value="{{ old('nip') }}"
                required maxlength="18"
                class="w-full border rounded-lg pl-10 pr-10 py-2.5 md:py-2 font-semibold placeholder-gray-400 focus:outline-none focus:ring focus:ring-blue-300 transition duration-200 focus:scale-[1.02]"
            >

        </div>


        {{-- PASSWORD --}}
        <div class="relative">

            {{-- ICON LOCK --}}
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5 text-gray-400"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M12 11c1.657 0 3-1.343 3-3V6a3 3 0 00-6 0v2c0 1.657 1.343 3 3 3z" />

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5 11h14v10H5z" />
                </svg>
            </div>

            {{-- INPUT --}}
            <input
                type="password"
                name="password"
                id="password"
                placeholder="PASSWORD"
                required
                class="w-full border rounded-lg pl-10 pr-10 py-2 font-semibold placeholder-gray-400
                    focus:outline-none focus:ring focus:ring-blue-300
                    transition duration-200 focus:scale-[1.02]"
            >

            {{-- EYE ICON --}}
            <button
                type="button"
                onclick="togglePassword()"
                class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-600"
            >

                {{-- eye open --}}
                <svg id="eyeOpen"
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12
                            18 18.75 12 18.75 2.25 12 2.25 12z" />

                    <circle cx="12" cy="12" r="3" stroke-width="2"/>
                </svg>


                {{-- eye closed --}}
                <svg id="eyeClosed"
                    xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5 hidden"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 3l18 18" />

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M10.58 10.58A3 3 0 0013.42 13.42" />

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M9.88 4.24A9.77 9.77 0 0112 3.75
                            c6 0 9.75 8.25 9.75 8.25
                            a17.7 17.7 0 01-4.2 5.5" />

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6.61 6.61A17.7 17.7 0 002.25 12
                            s3.75 8.25 9.75 8.25
                            a9.77 9.77 0 005.39-1.64" />

                </svg>

            </button>


        </div>


        {{-- ERROR --}}
        @error('login')
        <p class="text-red-600 text-sm">
            {{ $message }}
        </p>
        @enderror


        {{-- BUTTON --}}
        <button
            type="submit"
            id="loginBtn"
            class="w-full bg-[#182F54] hover:bg-blue-700 text-white py-2 rounded-lg font-semibold transition flex justify-center items-center"
        >

            <span id="btnText">Login</span>

            {{-- spinner --}}
            <svg id="spinner"
                class="animate-spin h-5 w-5 ml-2 hidden"
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24">

                <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4">
                </circle>

                <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8v8z">
                </path>

            </svg>

        </button>

        </form>

    </div>
    {{-- RIGHT : IMAGE --}}
    <div class="hidden md:block relative bg-white">
    <img src="{{ asset('image/auth/Main.png') }}"
     class="w-full mt-5 mb-5 pr-5 object-cover ">


        <div class=" bg-white"></div>
    </div>

    {{-- LOADING OVERLAY --}}
    <div id="loadingOverlay"
     class="absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center hidden z-50 rounded-xl">

        <div class="flex flex-col items-center gap-3">

            {{-- spinner --}}
            <svg class="w-10 h-10 animate-spin text-[#182F54]"
                 xmlns="http://www.w3.org/2000/svg"
                 fill="none"
                 viewBox="0 0 24 24">

                <circle
                    class="opacity-25"
                    cx="12"
                    cy="12"
                    r="10"
                    stroke="currentColor"
                    stroke-width="4">
                </circle>

                <path
                    class="opacity-75"
                    fill="currentColor"
                    d="M4 12a8 8 0 018-8v8z">
                </path>

            </svg>

            <span class="text-sm font-semibold text-gray-700">
                Signing in...
            </span>

        </div>

    </div>

    <script>
    
    function togglePassword()
    {
        const password = document.getElementById('password');
        const eyeOpen = document.getElementById('eyeOpen');
        const eyeClosed = document.getElementById('eyeClosed');
    
        if(password.type === "password")
        {
            password.type = "text";
    
            eyeOpen.classList.add("hidden");
            eyeClosed.classList.remove("hidden");
        }
        else
        {
            password.type = "password";
    
            eyeOpen.classList.remove("hidden");
            eyeClosed.classList.add("hidden");
        }
    }

    document.getElementById("loginForm").addEventListener("submit", function()
    {
        document.getElementById("loadingOverlay").classList.remove("hidden");
    });

    
    </script>

</div>
@endsection



