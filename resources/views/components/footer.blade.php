<footer class="bg-slate-900 text-slate-400 mt-auto" role="contentinfo">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-16">
            <div class="lg:col-span-1">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 text-white font-bold text-lg mb-5 group">
                    <span class="w-11 h-11 rounded-xl bg-violet-600 flex items-center justify-center text-white text-lg font-extrabold shadow-lg shadow-violet-500/20 group-hover:scale-105 transition-transform duration-300">C</span>
                    ChiclayoTicket
                </a>
                <p class="text-sm text-slate-400 leading-relaxed max-w-xs">
                    Compra entradas para conciertos, deportes, teatro y más. Tu marketplace de eventos de confianza.
                </p>
            </div>
            <div>
                <h3 class="text-white font-semibold text-sm uppercase tracking-wider mb-5">Eventos</h3>
                <ul class="space-y-3.5">
                    <li><a href="{{ route('events.index') }}" class="text-sm hover:text-violet-400 transition-colors duration-200">Todos los eventos</a></li>
                    <li><a href="{{ route('events.index', ['category_slug' => 'conciertos']) }}" class="text-sm hover:text-violet-400 transition-colors duration-200">Conciertos</a></li>
                    <li><a href="{{ route('events.index', ['category_slug' => 'deportes']) }}" class="text-sm hover:text-violet-400 transition-colors duration-200">Deportes</a></li>
                    <li><a href="{{ route('events.index', ['category_slug' => 'teatro']) }}" class="text-sm hover:text-violet-400 transition-colors duration-200">Teatro</a></li>
                    <li><a href="{{ route('events.index', ['category_slug' => 'conferencias']) }}" class="text-sm hover:text-violet-400 transition-colors duration-200">Conferencias</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-white font-semibold text-sm uppercase tracking-wider mb-5">Cuenta</h3>
                <ul class="space-y-3.5">
                    @auth
                        <li><a href="{{ route('orders.index') }}" class="text-sm hover:text-violet-400 transition-colors duration-200">Mis órdenes</a></li>
                        <li><a href="{{ route('logout') }}" class="text-sm hover:text-violet-400 transition-colors duration-200" onclick="event.preventDefault(); document.getElementById('footer-logout').submit();">Cerrar sesión</a></li>
                        <form id="footer-logout" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                    @else
                        <li><a href="{{ route('login') }}" class="text-sm hover:text-violet-400 transition-colors duration-200">Iniciar sesión</a></li>
                        <li><a href="{{ route('register') }}" class="text-sm hover:text-violet-400 transition-colors duration-200">Registrarse</a></li>
                    @endauth
                    <li><a href="{{ route('cart.index') }}" class="text-sm hover:text-violet-400 transition-colors duration-200">Resumen de compra</a></li>
                    <li><a href="{{ route('register') }}?role=organizer" class="text-sm hover:text-violet-400 transition-colors duration-200">Organizadores</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-white font-semibold text-sm uppercase tracking-wider mb-5">Contacto</h3>
                <ul class="space-y-3.5 text-sm">
                    <li><a href="mailto:soporte@chiclayoticket.com" class="hover:text-violet-400 transition-colors duration-200">soporte@chiclayoticket.com</a></li>
                    <li><a href="{{ route('libro-reclamaciones.create') }}" class="hover:text-violet-400 transition-colors duration-200">Libro de Reclamaciones</a></li>
                    <li class="text-slate-500">Lun - Vie, 9:00 - 18:00</li>
                </ul>
            </div>
        </div>
        <div class="mt-16 pt-8 border-t border-slate-800 flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-sm text-slate-500">&copy; {{ date('Y') }} ChiclayoTicket. Todos los derechos reservados.</p>
            <div class="flex gap-8 text-sm text-slate-500">
                <a href="#" class="hover:text-violet-400 transition-colors duration-200">Términos de uso</a>
                <a href="#" class="hover:text-violet-400 transition-colors duration-200">Privacidad</a>
            </div>
        </div>
    </div>
</footer>
