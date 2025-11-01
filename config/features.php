<?php
// config/features.php

return [
    'features' => [
        [
            'badge' => 'Pedidos',
            'title' => 'Todos tus pedidos en un solo lugar',
            'description' => 'Cargá tus pedidos de la manera más rápida y dinamica. El seguimiento de tus ventas nunca fue tan sencillo.',
            'features' => [
                'Crea pedidos en segundos',
                'Estados personalizables',
                'Agregá y gestioná clientes en el momento',
                'Te equivocaste? podes editarlos o cancelarlos cuando quieras',
                'Los pedidos actualizan el stock automáticamente asi no tenes que preocuparte por nada'
            ],
            'image' => 'images/features/pedidos.png',
            'inverted' => false,
            'background' => 'black'
        ],
        [
            'badge' => 'Stock',
            'title' => 'Que no te duela la cabeza por faltantes de stock',
            'description' => 'Control en tiempo real de todas tus sucursales. Vista dedicada para que veas todo, facil',
            'features' => [
                'Vista consolidada multi-sucursal',
                'Alertas de stock mínimo',
                'Transferencias entre sucursales',
                'Historial de movimientos, ideal para reportes'
            ],
            'image' => 'images/features/stock.png',
            'inverted' => true,
            'background' => 'black'
        ],
        [
            'badge' => 'Productos y Servicios',
            'title' => 'Tu catálogo completo, siempre actualizado',
            'description' => 'Organiza tu catálogo por categorías, marcas o como prefieras. Precios, costos y márgenes siempre a mano.',
            'features' => [
                'Carga tus productos y servicios fácilmente',
                'podes separarlos por sucursal',
                'Escaneo de códigos de barra para cargar productos y actualizar stock',
                'Vinculación con proveedores'
            ],
            'image' => 'images/features/productos.png',
            'inverted' => false,
            'background' => 'black'
        ],
        [
            'badge' => 'Empleados',
            'title' => 'Guardá y gestioná toda la informacion de tus empleados',
            'description' => 'Fichas de empleados completas. Agregá evaluaciones, capacitaciones y más.',
            'features' => [
                'Fichas descargables de empleados',
                'Asignación a sucursales',
                'Registro de actividad',
                'Evaluaciones de desempeño'
            ],
            'image' => 'images/features/empleados.png',
            'inverted' => false,
            'background' => 'black'
        ],
        [
            'badge' => 'Sucursales',
            'title' => 'Crece sin perder control',
            'description' => 'Una vista, múltiples ubicaciones. Compara rendimiento y gestiona cada sucursal.',
            'features' => [
                'Stock detallado por sucursal',
                'Administra cada sucursal en el mismo lugar',
                'Cada una tiene acceso a la plataforma, la manera mas eficiente de trabajar',
                'Reportes comparativos entre sucursales'
            ],
            'image' => 'images/features/sucursales.png',
            'inverted' => true,
            'background' => 'black'
        ],
        [
            'badge' => 'Gastos',
            'title' => 'Controla los gastos antes que te controlen',
            'description' => 'Calcula gastos al instante. Categoriza, por productos, elavoración o servicios.',
            'features' => [
                'Registro rápido gracias a la dinamica calculadora',
                'Carga tus insumos',
                'Elaborás productos? llevá el control de costos de elaboración',
                'Reportes por centro de costo'
            ],
            'image' => 'images/features/gastos.png',
            'inverted' => false,
            'background' => 'black'
        ],
    ]
];