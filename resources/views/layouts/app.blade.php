<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Dashboard' }} | SIMPEG</title>
    <link rel="icon" type="image/png" href="{{ asset('image/IconSimpegWarna.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
    <!-- Alpine.js -->
    {{-- <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}
    <!-- Reusable class -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- DATATABLE -->
    {{-- <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script> --}}

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.7/css/dataTables.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/4.0.6/css/fixedHeader.dataTables.min.css" />
    <script src="https://cdn.datatables.net/2.3.7/js/dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />
    <script src="https://cdn.datatables.net/fixedheader/4.0.6/js/dataTables.fixedHeader.min.js"></script>

    <script src="https://cdn.datatables.net/fixedcolumns/5.0.5/js/dataTables.fixedColumns.min.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/5.0.5/js/fixedColumns.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/5.0.5/css/fixedColumns.dataTables.min.css" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Google Font: Plus Jakarta Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    Usage
    <style>
        .filter-input {
            @apply w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm;
        }
    </style>
    <!-- Theme Store -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('theme', {
                init() {
                    const savedTheme = localStorage.getItem('theme');
                    const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' :
                        'light';
                    // this.theme = savedTheme || systemTheme;
                    this.theme = savedTheme || 'light';
                    this.updateTheme();
                },
                theme: 'light',
                toggle() {
                    this.theme = this.theme === 'light' ? 'dark' : 'light';
                    localStorage.setItem('theme', this.theme);
                    this.updateTheme();
                },
                updateTheme() {
                    const html = document.documentElement;
                    const body = document.body;
                    if (this.theme === 'dark') {
                        html.classList.add('dark');
                        body.classList.add('dark', 'bg-gray-900');
                    } else {
                        html.classList.remove('dark');
                        body.classList.remove('dark', 'bg-gray-900');
                    }
                }
            });

            Alpine.store('sidebar', {
                // Initialize based on screen size
                // isExpanded: window.innerWidth >= 1280, // true for desktop, false for mobile
                isExpanded: true, // default desktop
                isMobileOpen: false,
                isHovered: false,

                init() {
                    this.isExpanded = window.innerWidth >= 1280
                },

                toggleExpanded() {
                    this.isExpanded = !this.isExpanded;
                    // When toggling desktop sidebar, ensure mobile menu is closed
                    this.isMobileOpen = false;
                },

                toggleMobileOpen() {
                    this.isMobileOpen = !this.isMobileOpen;
                    // Don't modify isExpanded when toggling mobile menu
                },

                setMobileOpen(val) {
                    this.isMobileOpen = val;
                },

                setHovered(val) {
                    // Only allow hover effects on desktop when sidebar is collapsed
                    if (window.innerWidth >= 1280 && !this.isExpanded) {
                        this.isHovered = val;
                    }
                }
            });
        });
    </script>

    <!-- Apply dark mode immediately to prevent flash -->
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme');
            const systemTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            const theme = savedTheme || systemTheme;
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
                document.body.classList.add('dark', 'bg-gray-900');
            } else {
                document.documentElement.classList.remove('dark');
                // document.body.classList.remove('dark', 'bg-gray-900');
            }
        })();
    </script>
    <style>
        .fc {
            font-size: 0.85rem;
        }

        .fc .fc-daygrid-day-frame {
            padding: 2px;
        }
    </style>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                        },
                        dark: {
                            bg: '#0f172a',
                            card: '#1e293b',
                            border: '#334155',
                            text: '#f8fafc'
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #475569;
        }
    </style>

</head>

<!-- <body
    x-data="{ 'loaded': true }"
    x-init="$store.sidebar.isExpanded = window.innerWidth >= 1280;
    const checkMobile = () => {
        if (window.innerWidth < 1280) {
            $store.sidebar.setMobileOpen(false);
            $store.sidebar.isExpanded = false;
        } else {
            $store.sidebar.isMobileOpen = false;
            $store.sidebar.isExpanded = true;
        }
    };
    window.addEventListener('resize', checkMobile);"> -->

<body x-data="preloader()" x-init="init()">



    {{-- preloader --}}
    <x-common.preloader />
    {{-- preloader end --}}

    <div class="min-h-screen lg:flex">
        @include('layouts.backdrop')
        @include('layouts.sidebar')

        <div class="flex-1 transition-all duration-300 ease-in-out"
            :class="{
                'lg:ml-[290px]': $store.sidebar.isExpanded || $store.sidebar.isHovered,
                'lg:ml-[90px]': !$store.sidebar.isExpanded && !$store.sidebar.isHovered,
                'ml-0': $store.sidebar.isMobileOpen
            }">
            <!-- app header start -->
            @include('layouts.app-header')

            <!-- app header end -->
            <!-- <div class="p-4 mx-auto max-w-(--breakpoint-2xl) md:p-6"> -->
            <div class="bg-[#ECF3FF] h-full p-[24px]">
                @yield('content')
            </div>
        </div>

    </div>

    <div class="fixed top-5 right-5 space-y-3 w-[300px]">

        <template x-for="t in $store.toast.toasts" :key="t.id">
            <div x-show="t.show" x-transition
                class="relative overflow-hidden rounded-xl shadow-lg text-white p-4 flex justify-between items-start gap-3"
                :class="{
                    'bg-green-500': t.type === 'success',
                    'bg-red-500': t.type === 'error',
                    'bg-blue-500': t.type === 'info'
                }">

                <!-- ICON + TEXT -->
                <div class="flex gap-2 items-start">
                    <span x-show="t.type==='success'">✅</span>
                    <span x-show="t.type==='error'">❌</span>
                    <span x-show="t.type==='info'">ℹ️</span>

                    <span x-text="t.message"></span>
                </div>

                <!-- CLOSE BUTTON -->
                <button @click="$store.toast.remove(t.id)" class="text-white/80 hover:text-white text-sm">
                    ✕
                </button>

                <!-- PROGRESS BAR -->
                <!-- <div class="absolute bottom-0 left-0 h-1 bg-white/40 w-full">
                    <div class="h-full bg-white transition-all" :style="'width:' + t.progress + '%'"></div>
                </div> -->

            </div>
        </template>

    </div>

    <script>
        document.addEventListener('alpine:init', () => {

            Alpine.store('toast', {
                toasts: [],
                counter: 0,

                show(message, type = 'success', duration = 3000) {

                    let id = this.counter++

                    let toast = {
                        id,
                        message,
                        type,
                        show: true,
                        progress: 100
                    }

                    this.toasts.push(toast)

                    // auto hide (kalau duration != 0)
                    if (duration > 0) {

                        let interval = setInterval(() => {
                            toast.progress -= (100 / (duration / 100))

                            if (toast.progress <= 0) {
                                clearInterval(interval)
                                this.remove(id)
                            }
                        }, 100)

                    }
                },

                remove(id) {
                    let t = this.toasts.find(t => t.id === id)
                    if (t) t.show = false

                    setTimeout(() => {
                        this.toasts = this.toasts.filter(t => t.id !== id)
                    }, 300)
                }

            })

            // global helper
            window.showToast = (msg, type = 'success', duration = 3000) => {
                Alpine.store('toast').show(msg, type, duration)
            }

        })
    </script>
    <script>
        // function preloader() {
        //     return {
        //         loaded: false,
        //         progress: 0,
        //         interval: null,

        //         init() {

        //             this.startFakeProgress()

        //             window.addEventListener('load', () => {
        //                 this.finishLoading()
        //             })

        //         },

        //         startFakeProgress() {

        //             this.interval = setInterval(() => {

        //                 if (this.progress < 90) {
        //                     this.progress += Math.random() * 8
        //                 }

        //             }, 200)

        //         },

        //         finishLoading() {

        //             clearInterval(this.interval)

        //             this.progress = 100

        //             setTimeout(() => {
        //                 this.loaded = true
        //             }, 400)

        //         }
        //     }
        // }
    </script>
    <script>
        window.SwalHelper = {

            success(message = 'Berhasil') {
                Swal.fire({
                    icon: 'success',
                    title: 'Sukses',
                    text: message,
                    timer: 2000,
                    showConfirmButton: false
                })
            },
            toast(message, type = 'success') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: type,
                    title: message,
                    showConfirmButton: false,
                    timer: 2000
                })
            },
            loadingToast(message = 'Memproses...') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    title: message,
                    zIndex: 1001,
                    // allowOutsideClick: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                })
            },
            errorToast(message = 'Terjadi kesalahan ❌') {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: message,
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true
                })
            },

            error(message = 'Terjadi kesalahan') {
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: message
                })
            },

            confirm(message = 'Yakin?') {
                return Swal.fire({
                    title: 'Konfirmasi',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya',
                    cancelButtonText: 'Batal'
                })
            },

            loading(message = 'Memproses...') {
                Swal.fire({
                    title: message,
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading()
                    }
                })
            },

            close() {
                Swal.close()
            }
        }
    </script>

</body>

@stack('scripts')

</html>