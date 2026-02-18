<footer class="bg-neutral-900 text-neutral-400 mt-auto" role="contentinfo">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 lg:py-14">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10 lg:gap-12">
            <div class="lg:col-span-1">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2.5 text-white font-bold text-lg mb-4">
                    <span class="w-10 h-10 rounded-xl bg-[#00a650] flex items-center justify-center text-white text-lg font-extrabold">L</span>
                    LogicTicket
                </a>
                <p class="text-sm text-neutral-400 leading-relaxed max-w-xs">
                    Eventos en tu ciudad. Compra entradas para conciertos, deportes, teatro y más.
                </p>
            </div>
            <div>
                <h3 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">Eventos</h3>
                <ul class="space-y-3">
                    <li><a href="{{ route('events.index') }}" class="text-sm hover:text-[#00a650] transition-colors">Todos los eventos</a></li>
                    <li><a href="{{ route('events.index', ['category_slug' => 'conciertos']) }}" class="text-sm hover:text-[#00a650] transition-colors">Conciertos</a></li>
                    <li><a href="{{ route('events.index', ['category_slug' => 'deportes']) }}" class="text-sm hover:text-[#00a650] transition-colors">Deportes</a></li>
                    <li><a href="{{ route('events.index', ['category_slug' => 'teatro']) }}" class="text-sm hover:text-[#00a650] transition-colors">Teatro</a></li>
                    <li><a href="{{ route('events.index', ['category_slug' => 'conferencias']) }}" class="text-sm hover:text-[#00a650] transition-colors">Conferencias</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">Cuenta</h3>
                <ul class="space-y-3">
                    @auth
                        <li><a href="{{ route('orders.index') }}" class="text-sm hover:text-[#00a650] transition-colors">Mis órdenes</a></li>
                        <li><a href="{{ route('logout') }}" class="text-sm hover:text-[#00a650] transition-colors" onclick="event.preventDefault(); document.getElementById('footer-logout').submit();">Cerrar sesión</a></li>
                        <form id="footer-logout" action="{{ route('logout') }}" method="POST" class="hidden">@csrf</form>
                    @else
                        <li><a href="{{ route('login') }}" class="text-sm hover:text-[#00a650] transition-colors">Iniciar sesión</a></li>
                        <li><a href="{{ route('register') }}" class="text-sm hover:text-[#00a650] transition-colors">Registrarse</a></li>
                    @endauth
                    <li><a href="{{ route('cart.index') }}" class="text-sm hover:text-[#00a650] transition-colors">Carrito</a></li>
                    <li><a href="{{ route('register') }}?role=organizer" class="text-sm hover:text-[#00a650] transition-colors">Organizadores</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-white font-semibold text-sm uppercase tracking-wider mb-4">Contacto</h3>
                <ul class="space-y-3 text-sm">
                    <li><a href="mailto:soporte@logicticket.com" class="hover:text-[#00a650] transition-colors">soporte@logicticket.com</a></li>
                    <li><a href="{{ route('libro-reclamaciones.create') }}" class="hover:text-[#00a650] transition-colors">Libro de Reclamaciones</a></li>
                    <li class="text-neutral-500">Lun - Vie, 9:00 - 18:00</li>
                </ul>
            </div>
        </div>
        <div class="mt-12 pt-8 border-t border-neutral-800 flex flex-col sm:flex-row justify-between items-center gap-4">
            <p class="text-sm text-neutral-500">&copy; {{ date('Y') }} LogicTicket. Todos los derechos reservados.</p>
            <div class="flex gap-6 text-sm text-neutral-500">
                <a href="#" class="hover:text-[#00a650] transition-colors">Términos de uso</a>
                <a href="#" class="hover:text-[#00a650] transition-colors">Privacidad</a>
            </div>
        </div>
    </div>
</footer>
