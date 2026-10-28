<?php
$activePage = $activePage ?? '';
?>

<aside class="w-72 h-screen fixed left-0 top-0 bg-white border-r border-slate-200 z-50">
    
    <div class="p-8 flex items-center gap-4">
        <div class="w-12 h-12 bg-indigo-600 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-200">
            <span class="text-white font-black text-xl tracking-tighter">SK</span>
        </div>
        <div>
            <h1 class="text-xl font-bold text-slate-800 tracking-tight leading-none">SiKos</h1>
            <span class="text-[10px] font-bold text-indigo-500 uppercase tracking-[0.2em]">Admin</span>
        </div>
    </div>

    <nav class="flex-1 px-4 py-4 space-y-8 overflow-y-auto no-scrollbar">
        
        <!-- DASHBOARD -->
        <div>
            <a href="/manajemen_kos/dashboard.php"
               class="flex items-center gap-4 px-4 py-3 rounded-2xl transition-all group
               <?= ($activePage=='dashboard') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-500 hover:text-indigo-600 hover:bg-slate-50' ?>">

                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M3 13h1v7c0 1.103.897 2 2 2h12c1.103 0 2-.897 2-2v-7h1a1 1 0 0 0 .707-1.707l-9-9a.999.999 0 0 0-1.414 0l-9 9A1 1 0 0 0 3 13zm7 7v-5h4v5h-4z"></path></svg>

                <span class="font-bold text-sm">Dashboard</span>
            </a>
        </div>

        <!-- DATA -->
        <div>
            <p class="px-4 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Kelola Data</p>

            <ul class="space-y-1">

                <!-- KAMAR -->
                <li>
                    <a href="/manajemen_kos/modules/kamar/index.php"
                       class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all group
                       <?= ($activePage=='kamar') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-500 hover:text-indigo-600 hover:bg-slate-50' ?>">

                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 2H5c-1.103 0-2 .897-2 2v16c0 1.103.897 2 2 2h14c1.103 0 2-.897 2-2V4c0-1.103-.897-2-2-2zm-7 13c-1.654 0-3-1.346-3-3s1.346-3 3-3 3 1.346 3 3-1.346 3-3 3z"></path></svg>

                        <span class="font-bold text-sm">Data Kamar</span>
                    </a>
                </li>

                <!-- PENGHUNI -->
                <li>
                    <a href="/manajemen_kos/modules/penghuni/index.php"
                       class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all group
                       <?= ($activePage=='penghuni') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-500 hover:text-indigo-600 hover:bg-slate-50' ?>">

                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"></path><path d="M12 6c-1.93 0-3.5 1.57-3.5 3.5S10.07 13 12 13s3.5-1.57 3.5-3.5S13.93 6 12 6zm0 5c-.827 0-1.5-.673-1.5-1.5S11.173 8 12 8s1.5.673 1.5 1.5S12.827 11 12 11zm0 3c-4.418 0-8 2.015-8 4.5V19h16v-.5c0-2.485-3.582-4.5-8-4.5z"></path></svg>

                        <span class="font-bold text-sm">Data Penghuni</span>
                    </a>
                </li>

            </ul>
        </div>

        <!-- TRANSAKSI -->
        <div>
            <p class="px-4 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">Transaksi</p>

            <ul class="space-y-1">

                <li>
                    <a href="/manajemen_kos/modules/pembayaran/index.php"
                       class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all group 
                       <?= ($activePage=='pembayaran') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-500 hover:text-indigo-600 hover:bg-slate-50' ?>">

                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M21 4H3a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1zm-1 14H4V6h16v12z"></path><path d="M6 10h2v2H6zm0 4h2v2H6zm10-4h2v2h-2zm-4 4h2v2h-2zm0-4h2v2h-2zm4 4h2v2h-2z"></path></svg>

                        <span class="font-bold text-sm">Pembayaran</span>
                    </a>
                </li>

                <li>
                    <a href="/manajemen_kos/modules/pengeluaran/index.php"
                       class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all group 
                       <?= ($activePage=='pengeluaran') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-500 hover:text-indigo-600 hover:bg-slate-50' ?>">

                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M16 12h2v4h-2z"></path><path d="M20 7V5c0-1.103-.897-2-2-2H4c-1.103 0-2 .897-2 2v14c0 1.103.897 2 2 2h16c1.103 0 2-.897 2-2V9c0-1.103-.897-2-2-2zM4 19V5h14v2H4v2h16v10H4z"></path></svg>

                        <span class="font-bold text-sm">Pengeluaran</span>
                    </a>
                </li>

                <li>
                    <a href="/manajemen_kos/modules/laporan/index.php"
                       class="flex items-center gap-4 px-4 py-3 rounded-xl transition-all group 
                       <?= ($activePage=='laporan') ? 'bg-indigo-50 text-indigo-700' : 'text-slate-500 hover:text-indigo-600 hover:bg-slate-50' ?>">

                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 8h-2v3h2V8zm-4 0h-2v3h2V8zm-4 0H9v3h2V8zm-4 0H5v3h2V8z"></path><path d="M18 2H6c-1.103 0-2 .897-2 2v18l5-3 5 3 5-3 5 3V4c0-1.103-.897-2-2-2zM6 19.144V4h12v15.144l-4-2.4-2 1.2-2-1.2-4 2.4z"></path></svg>

                        <span class="font-bold text-sm">Cetak Laporan</span>
                    </a>
                </li>

            </ul>
        </div>

    </nav>

    <!-- BOTTOM -->
    <div class="p-6 border-t border-slate-100 space-y-2">

        <a href="/manajemen_kos/auth/ganti_password.php"
           class="flex items-center gap-4 px-4 py-3 rounded-xl transition group text-slate-500 hover:text-indigo-600 hover:bg-slate-50">

            <svg class="w-5 h-5 text-slate-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>

            <span class="font-bold text-sm">Ganti Password</span>
        </a>

        <a href="/manajemen_kos/auth/logout.php"
           class="flex items-center gap-4 px-4 py-3 rounded-xl transition group text-rose-500 hover:bg-rose-50">

            <svg class="w-5 h-5 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>

            <span class="font-bold text-sm">Logout</span>
        </a>

    </div>

</aside>