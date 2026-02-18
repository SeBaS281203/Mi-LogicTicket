<?php

namespace Database\Seeders;

use App\Models\Banner;
use App\Models\Category;
use App\Models\City;
use App\Models\Event;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Setting;
use App\Models\Ticket;
use App\Models\TicketType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CategorySeeder::class,
            CitySeeder::class,
            SettingSeeder::class,
            EventSeeder::class,
            BannerSeeder::class,
            OrderSeeder::class,
            LibroReclamacionSeeder::class,
        ]);
    }
}

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            ['name' => 'Admin', 'email' => 'admin@logicticket.com', 'password' => 'password', 'role' => 'admin', 'phone' => null],
            ['name' => 'Organizador Demo', 'email' => 'organizer@logicticket.com', 'password' => 'password', 'role' => 'organizer', 'phone' => '999111222'],
            ['name' => 'María Producciones', 'email' => 'maria@logicticket.com', 'password' => 'password', 'role' => 'organizer', 'phone' => '999333444'],
            ['name' => 'Cliente Demo', 'email' => 'client@logicticket.com', 'password' => 'password', 'role' => 'client', 'phone' => '999555666'],
            ['name' => 'Juan Pérez', 'email' => 'juan@logicticket.com', 'password' => 'password', 'role' => 'client', 'phone' => null],
        ];
        foreach ($users as $u) {
            User::updateOrCreate(
                ['email' => $u['email']],
                [
                    'name' => $u['name'],
                    'password' => Hash::make($u['password']),
                    'role' => $u['role'],
                    'phone' => $u['phone'],
                ]
            );
        }
    }
}

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Conciertos', 'slug' => 'conciertos', 'description' => 'Conciertos y festivales de música', 'is_active' => true],
            ['name' => 'Deportes', 'slug' => 'deportes', 'description' => 'Eventos deportivos', 'is_active' => true],
            ['name' => 'Teatro', 'slug' => 'teatro', 'description' => 'Obras de teatro y musicales', 'is_active' => true],
            ['name' => 'Conferencias', 'slug' => 'conferencias', 'description' => 'Charlas y conferencias', 'is_active' => true],
            ['name' => 'Fiestas', 'slug' => 'fiestas', 'description' => 'Fiestas y eventos nocturnos', 'is_active' => true],
            ['name' => 'Gastronomía', 'slug' => 'gastronomia', 'description' => 'Ferias y eventos gastronómicos', 'is_active' => true],
        ];
        foreach ($items as $item) {
            Category::updateOrCreate(['slug' => $item['slug']], $item);
        }
    }
}

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'Lima', 'slug' => 'lima', 'country' => 'Peru', 'sort_order' => 1],
            ['name' => 'Arequipa', 'slug' => 'arequipa', 'country' => 'Peru', 'sort_order' => 2],
            ['name' => 'Cusco', 'slug' => 'cusco', 'country' => 'Peru', 'sort_order' => 3],
            ['name' => 'Trujillo', 'slug' => 'trujillo', 'country' => 'Peru', 'sort_order' => 4],
            ['name' => 'Chiclayo', 'slug' => 'chiclayo', 'country' => 'Peru', 'sort_order' => 5],
        ];
        foreach ($items as $item) {
            City::updateOrCreate(['slug' => $item['slug']], array_merge($item, ['is_active' => true]));
        }
    }
}

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::set('commission_percentage', 5);
    }
}

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $organizer = User::where('role', 'organizer')->first();
        $organizer2 = User::where('role', 'organizer')->skip(1)->first() ?? $organizer;
        $categories = Category::all();
        $cities = City::all();

        $eventImages = [
            'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?w=800',
            'https://images.unsplash.com/photo-1461897104016-0b3b00cc81ee?w=800',
            'https://images.unsplash.com/photo-1503095396549-807759245b35?w=800',
            'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=800',
            'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=800',
            'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=800',
            'https://images.unsplash.com/photo-1516450360452-9312f5e86fc7?w=800',
        ];
        $eventsData = [
            ['title' => 'Festival de Rock 2025', 'status' => 'published', 'venue' => 'Estadio Nacional', 'city' => 'Lima'],
            ['title' => 'Maratón de Lima', 'status' => 'published', 'venue' => 'Costa Verde', 'city' => 'Lima'],
            ['title' => 'Obra: Romeo y Julieta', 'status' => 'published', 'venue' => 'Teatro Municipal', 'city' => 'Lima'],
            ['title' => 'Conferencia Tech 2025', 'status' => 'draft', 'venue' => 'Centro de Convenciones', 'city' => 'Lima'],
            ['title' => 'Fiesta de Año Nuevo', 'status' => 'draft', 'venue' => 'Salón VIP', 'city' => 'Arequipa'],
            ['title' => 'Concierto de Cumbia', 'status' => 'published', 'venue' => 'Coliseo Arequipa', 'city' => 'Arequipa'],
            ['title' => 'Inti Raymi 2025', 'status' => 'published', 'venue' => 'Sacsayhuamán', 'city' => 'Cusco'],
        ];

        foreach ($eventsData as $i => $data) {
            $cat = $categories->random();
            $city = $cities->firstWhere('name', $data['city']) ?? $cities->random();
            $user = $i % 2 === 0 ? $organizer : $organizer2;
            $start = now()->addDays(rand(7, 90))->setHour(19)->setMinute(0);
            $end = (clone $start)->addHours(3);
            $slug = Str::slug($data['title']) . '-' . substr(uniqid(), -6);

            $event = Event::firstOrCreate(
                ['title' => $data['title']],
                [
                    'slug' => $slug,
                    'user_id' => $user->id,
                    'category_id' => $cat->id,
                    'title' => $data['title'],
                    'description' => 'Descripción del evento ' . $data['title'] . '. Ven y disfruta con nosotros.',
                    'venue_name' => $data['venue'],
                    'venue_address' => fake()->streetAddress(),
                    'city' => $city->name,
                    'country' => 'Peru',
                    'start_date' => $start,
                    'end_date' => $end,
                    'status' => $data['status'],
                    'ticket_price' => 0,
                    'available_tickets' => 0,
                    'event_image' => $eventImages[$i] ?? null,
                ]
            );
            if (isset($eventImages[$i])) {
                $event->update(['event_image' => $eventImages[$i]]);
            }

            if ($event->ticketTypes()->count() === 0) {
                TicketType::create([
                    'event_id' => $event->id,
                    'name' => 'General',
                    'price' => rand(30, 150),
                    'quantity' => rand(100, 500),
                ]);
                TicketType::create([
                    'event_id' => $event->id,
                    'name' => 'VIP',
                    'price' => rand(150, 400),
                    'quantity' => rand(20, 80),
                ]);
            }
        }
    }
}

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['title' => '¡Eventos destacados!', 'subtitle' => 'Encuentra las mejores entradas', 'link_url' => '/eventos', 'link_text' => 'Ver eventos', 'sort_order' => 1, 'image' => 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?w=1920'],
            ['title' => 'Ofertas de temporada', 'subtitle' => 'Descuentos en entradas seleccionadas', 'link_url' => '/eventos', 'link_text' => 'Explorar', 'sort_order' => 2, 'image' => 'https://images.unsplash.com/photo-1470229722913-7c0e2dbbafd3?w=1920'],
        ];
        foreach ($items as $item) {
            Banner::updateOrCreate(
                ['title' => $item['title']],
                array_merge($item, ['is_active' => true])
            );
        }
    }
}

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $client = User::where('role', 'client')->first();
        $event = Event::where('status', 'published')->first();
        if (!$event || !$client) {
            return;
        }
        $ticketType = $event->ticketTypes()->first();
        if (!$ticketType) {
            return;
        }
        $qty = 2;
        $subtotal = $ticketType->price * $qty;
        $commission = round($subtotal * 0.05, 2);
        $total = $subtotal + $commission;

        $order = Order::firstOrCreate(
            ['order_number' => 'LT-DEMO001-20250221'],
            [
                'user_id' => $client->id,
                'customer_email' => $client->email,
                'customer_name' => $client->name,
                'customer_phone' => $client->phone,
                'subtotal' => $subtotal,
                'commission_amount' => $commission,
                'total' => $total,
                'status' => 'paid',
                'payment_method' => 'manual',
            ]
        );

        if ($order->items()->count() === 0) {
            $item = OrderItem::create([
                'order_id' => $order->id,
                'ticket_type_id' => $ticketType->id,
                'event_id' => $event->id,
                'ticket_type_name' => $ticketType->name,
                'event_title' => $event->title,
                'quantity' => $qty,
                'unit_price' => $ticketType->price,
                'subtotal' => $subtotal,
            ]);
            $ticketType->increment('quantity_sold', $qty);
            for ($i = 0; $i < $qty; $i++) {
                Ticket::create([
                    'order_item_id' => $item->id,
                    'code' => Ticket::generateUniqueCode(),
                ]);
            }
        }
    }
}
