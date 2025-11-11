<x-guest-layout>
    <div class="min-h-screen bg-black py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-16">
                <h1 class="text-4xl sm:text-5xl font-bold text-white mb-3">
                    Política de Privacidad
                </h1>
                <p class="text-gray-400">
                    Última actualización: {{ date('d/m/Y') }}
                </p>
            </div>

            <!-- Content -->
            <div class="bg-gray-900/30 backdrop-blur-sm rounded-2xl p-8 sm:p-12">
                <!-- Índice / Table of contents -->
                <div class="mb-10 rounded-xl border border-white/10 bg-white/5 p-6">
                    <h2 class="text-xl font-semibold text-white mb-4">Contenido</h2>
                    <ol class="grid gap-2 sm:grid-cols-2 list-decimal pl-5 marker:text-purple-400">
                        <li><a class="text-gray-300 hover:text-white transition-colors" href="#introduccion">1. Introducción</a></li>
                        <li><a class="text-gray-300 hover:text-white transition-colors" href="#informacion">2. Información que Recopilamos</a></li>
                        <li><a class="text-gray-300 hover:text-white transition-colors" href="#uso">3. Cómo Utilizamos su Información</a></li>
                        <li><a class="text-gray-300 hover:text-white transition-colors" href="#base-legal">4. Base Legal para el Procesamiento</a></li>
                        <li><a class="text-gray-300 hover:text-white transition-colors" href="#terceros">5. Compartir Información con Terceros</a></li>
                        <li><a class="text-gray-300 hover:text-white transition-colors" href="#seguridad">6. Seguridad de los Datos</a></li>
                        <li><a class="text-gray-300 hover:text-white transition-colors" href="#retencion">7. Retención de Datos</a></li>
                        <li><a class="text-gray-300 hover:text-white transition-colors" href="#derechos">8. Sus Derechos de Privacidad</a></li>
                        <li><a class="text-gray-300 hover:text-white transition-colors" href="#cookies">9. Cookies y Tecnologías de Seguimiento</a></li>
                        <li><a class="text-gray-300 hover:text-white transition-colors" href="#transferencias">10. Transferencias Internacionales de Datos</a></li>
                        <li><a class="text-gray-300 hover:text-white transition-colors" href="#menores">11. Privacidad de Menores</a></li>
                        <li><a class="text-gray-300 hover:text-white transition-colors" href="#cambios">12. Cambios a esta Política de Privacidad</a></li>
                        <li><a class="text-gray-300 hover:text-white transition-colors" href="#contacto">13. Contacto</a></li>
                        <li><a class="text-gray-300 hover:text-white transition-colors" href="#consentimiento">14. Consentimiento</a></li>
                    </ol>
                </div>

                <div class="prose prose-invert max-w-none text-gray-200 leading-relaxed prose-headings:text-white prose-a:text-purple-400 hover:prose-a:text-purple-300 prose-strong:text-white prose-li:marker:text-purple-400 space-y-10">
                    <section>
                        <h2 id="introduccion" class="text-2xl font-bold mb-4 text-white">1. Introducción</h2>
                        <p>
                            En Gestior ("nosotros", "nuestro" o "la Plataforma"), respetamos su privacidad y nos comprometemos
                            a proteger sus datos personales. Esta Política de Privacidad explica cómo recopilamos, usamos,
                            almacenamos y protegemos su información cuando utiliza nuestros servicios de gestión empresarial.
                        </p>
                        <p>
                            Gestior es una plataforma de gestión empresarial que permite a las empresas administrar sus operaciones,
                            incluyendo ventas, inventario, clientes y sucursales de manera eficiente y segura.
                        </p>
                    </section>

                    <section>
                        <h2 id="informacion" class="text-2xl font-bold mb-4 text-white">2. Información que Recopilamos</h2>

                        <h3 class="text-lg font-semibold mt-6 mb-3 text-gray-100">2.1 Información de Cuenta y Perfil</h3>
                        <ul class="list-disc pl-6 space-y-2">
                            <li><strong>Datos de registro:</strong> Nombre completo, dirección de correo electrónico y contraseña (encriptada)</li>
                            <li><strong>Información de perfil:</strong> Foto de perfil (opcional), configuraciones de usuario</li>
                            <li><strong>Datos de autenticación:</strong> Información de verificación de correo electrónico, códigos de autenticación de dos factores (si está habilitado)</li>
                            <li><strong>Datos de organización:</strong> Nivel de jerarquía en la organización, relaciones padre-hijo entre usuarios, límites de usuarios y sucursales</li>
                        </ul>

                        <h3 class="text-lg font-semibold mt-6 mb-3 text-gray-100">2.2 Información de Suscripción</h3>
                        <ul class="list-disc pl-6 space-y-2">
                            <li><strong>Plan de suscripción:</strong> Nivel de suscripción (Básico, Premium, Enterprise)</li>
                            <li><strong>Códigos de invitación:</strong> Códigos de activación utilizados, historial de invitaciones</li>
                            <li><strong>Estado de cuenta:</strong> Estado de activación, límites de funcionalidades según el plan</li>
                        </ul>

                        <h3 class="text-lg font-semibold mt-6 mb-3 text-gray-100">2.3 Datos de Negocio y Clientes</h3>
                        <ul class="list-disc pl-6 space-y-2">
                            <li><strong>Información de clientes:</strong> Nombres, correos electrónicos, números de teléfono, números de documento, direcciones, información de empresas</li>
                            <li><strong>Datos de sucursales:</strong> Nombres de sucursales, ubicaciones, configuraciones</li>
                            <li><strong>Información de empleados:</strong> Datos de empleados asociados a su organización</li>
                        </ul>

                        <h3 class="text-lg font-semibold mt-6 mb-3 text-gray-100">2.4 Datos de Operaciones Comerciales</h3>
                        <ul class="list-disc pl-6 space-y-2">
                            <li><strong>Órdenes y ventas:</strong> Información de pedidos, números de orden, totales, descuentos, impuestos</li>
                            <li><strong>Inventario:</strong> Productos, insumos, recetas, niveles de stock, ajustes de inventario</li>
                            <li><strong>Métodos de pago:</strong> Tipos de métodos de pago utilizados (no almacenamos información completa de tarjetas de crédito)</li>
                            <li><strong>Servicios:</strong> Información sobre servicios ofrecidos, precios, insumos asociados</li>
                        </ul>

                        <h3 class="text-lg font-semibold mt-6 mb-3 text-gray-100">2.5 Datos de Uso y Técnicos</h3>
                        <ul class="list-disc pl-6 space-y-2">
                            <li><strong>Información del dispositivo:</strong> Tipo de navegador, sistema operativo, dirección IP</li>
                            <li><strong>Datos de uso:</strong> Páginas visitadas, funciones utilizadas, tiempo de uso</li>
                            <li><strong>Cookies y tecnologías similares:</strong> Utilizadas para mantener su sesión activa y mejorar la experiencia</li>
                            <li><strong>Logs del sistema:</strong> Registros de actividad para seguridad y resolución de problemas</li>
                        </ul>

                        <h3 class="text-lg font-semibold mt-6 mb-3 text-gray-100">2.6 Comunicaciones</h3>
                        <ul class="list-disc pl-6 space-y-2">
                            <li><strong>Correos electrónicos:</strong> Correspondencia con nuestro equipo de soporte</li>
                            <li><strong>Notificaciones:</strong> Configuraciones de notificaciones de stock, alertas del sistema</li>
                            <li><strong>Mensajes de soporte:</strong> Tickets de soporte, chats de ayuda</li>
                        </ul>
                    </section>

                    <section>
                        <h2 id="uso" class="text-2xl font-bold mb-4 text-white">3. Cómo Utilizamos su Información</h2>
                        <p>Utilizamos la información recopilada para los siguientes propósitos:</p>

                        <h3 class="text-lg font-semibold mt-6 mb-3 text-gray-100">3.1 Provisión de Servicios</h3>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Crear y administrar su cuenta de usuario</li>
                            <li>Procesar y gestionar sus suscripciones</li>
                            <li>Permitir la funcionalidad de gestión empresarial (ventas, inventario, clientes)</li>
                            <li>Facilitar la colaboración entre usuarios de su organización</li>
                            <li>Generar reportes y analíticas de negocio</li>
                        </ul>

                        <h3 class="text-lg font-semibold mt-6 mb-3 text-gray-100">3.2 Comunicación</h3>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Enviar correos de verificación de cuenta</li>
                            <li>Notificar sobre cambios en su cuenta o servicios</li>
                            <li>Enviar notificaciones de stock bajo o agotado (si está configurado)</li>
                            <li>Responder a sus consultas de soporte</li>
                            <li>Enviar actualizaciones importantes sobre el servicio</li>
                        </ul>

                        <h3 class="text-lg font-semibold mt-6 mb-3 text-gray-100">3.3 Seguridad y Prevención de Fraude</h3>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Verificar su identidad y prevenir accesos no autorizados</li>
                            <li>Detectar y prevenir actividades fraudulentas o ilegales</li>
                            <li>Proteger la seguridad e integridad de nuestros sistemas</li>
                            <li>Cumplir con obligaciones legales y regulatorias</li>
                        </ul>

                        <h3 class="text-lg font-semibold mt-6 mb-3 text-gray-100">3.4 Mejora de Servicios</h3>
                        <ul class="list-disc pl-6 space-y-2">
                            <li>Analizar el uso de la plataforma para mejorar funcionalidades</li>
                            <li>Desarrollar nuevas características basadas en necesidades de usuarios</li>
                            <li>Optimizar el rendimiento y la experiencia del usuario</li>
                            <li>Realizar investigación y análisis de datos agregados y anonimizados</li>
                        </ul>
                    </section>

                    <section>
                        <h2 id="base-legal" class="text-2xl font-bold mb-4 text-white">4. Base Legal para el Procesamiento</h2>
                        <p>Procesamos sus datos personales bajo las siguientes bases legales:</p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li><strong>Ejecución de contrato:</strong> Para proporcionar los servicios que ha contratado</li>
                            <li><strong>Consentimiento:</strong> Cuando nos ha dado permiso explícito para procesar sus datos</li>
                            <li><strong>Interés legítimo:</strong> Para mejorar nuestros servicios, prevenir fraudes y garantizar la seguridad</li>
                            <li><strong>Obligación legal:</strong> Para cumplir con requisitos legales y regulatorios</li>
                        </ul>
                    </section>

                    <section>
                        <h2 id="terceros" class="text-2xl font-bold mb-4 text-white">5. Compartir Información con Terceros</h2>
                        <p>
                            No vendemos ni alquilamos su información personal a terceros. Podemos compartir su información
                            únicamente en las siguientes circunstancias:
                        </p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li><strong>Proveedores de servicios:</strong> Con proveedores que nos ayudan a operar nuestra plataforma (hosting, análisis, soporte), bajo estrictos acuerdos de confidencialidad</li>
                            <li><strong>Requisitos legales:</strong> Cuando sea requerido por ley, orden judicial o regulación gubernamental</li>
                            <li><strong>Protección de derechos:</strong> Para proteger nuestros derechos legales, propiedad o seguridad, o la de nuestros usuarios</li>
                            <li><strong>Transferencia de negocio:</strong> En caso de fusión, adquisición o venta de activos, previa notificación</li>
                            <li><strong>Con su consentimiento:</strong> En cualquier otro caso, solo con su autorización explícita</li>
                        </ul>
                    </section>

                    <section>
                        <h2 id="seguridad" class="text-2xl font-bold mb-4 text-white">6. Seguridad de los Datos</h2>
                        <p>Implementamos medidas de seguridad técnicas y organizativas para proteger sus datos:</p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li><strong>Encriptación:</strong> Las contraseñas se almacenan encriptadas usando algoritmos seguros (bcrypt)</li>
                            <li><strong>HTTPS/SSL:</strong> Todas las comunicaciones se realizan a través de conexiones seguras</li>
                            <li><strong>Control de acceso:</strong> Acceso restringido a datos personales solo para personal autorizado</li>
                            <li><strong>Autenticación de dos factores:</strong> Disponible para mayor seguridad de la cuenta</li>
                            <li><strong>Monitoreo:</strong> Supervisión continua de sistemas para detectar y prevenir brechas de seguridad</li>
                            <li><strong>Respaldo de datos:</strong> Copias de seguridad regulares para prevenir pérdida de información</li>
                            <li><strong>Soft deletes:</strong> Los datos eliminados se conservan temporalmente para permitir recuperación</li>
                        </ul>
                        <p class="mt-3">
                            Sin embargo, ningún método de transmisión por Internet o almacenamiento electrónico es 100% seguro.
                            Aunque nos esforzamos por proteger sus datos, no podemos garantizar su seguridad absoluta.
                        </p>
                    </section>

                    <section>
                        <h2 id="retencion" class="text-2xl font-bold mb-4 text-white">7. Retención de Datos</h2>
                        <p>
                            Conservamos sus datos personales durante el tiempo necesario para cumplir con los propósitos
                            descritos en esta política:
                        </p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li><strong>Datos de cuenta activa:</strong> Mientras su cuenta permanezca activa</li>
                            <li><strong>Datos de cuenta eliminada:</strong> Se conservan temporalmente (soft delete) y luego se eliminan permanentemente según nuestras políticas de retención</li>
                            <li><strong>Datos de transacciones:</strong> Se conservan según requisitos legales y contables (generalmente 5-10 años)</li>
                            <li><strong>Datos de auditoría:</strong> Logs de seguridad se conservan por períodos razonables para fines de seguridad</li>
                            <li><strong>Datos de marketing:</strong> Hasta que retire su consentimiento o solicite eliminación</li>
                        </ul>
                    </section>

                    <section>
                        <h2 id="derechos" class="text-2xl font-bold mb-4 text-white">8. Sus Derechos de Privacidad</h2>
                        <p>Dependiendo de su ubicación, puede tener los siguientes derechos:</p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li><strong>Acceso:</strong> Solicitar una copia de los datos personales que tenemos sobre usted</li>
                            <li><strong>Rectificación:</strong> Solicitar la corrección de datos inexactos o incompletos</li>
                            <li><strong>Eliminación:</strong> Solicitar la eliminación de sus datos personales ("derecho al olvido")</li>
                            <li><strong>Portabilidad:</strong> Solicitar la transferencia de sus datos a otro servicio</li>
                            <li><strong>Restricción:</strong> Solicitar la limitación del procesamiento de sus datos</li>
                            <li><strong>Oposición:</strong> Oponerse al procesamiento de sus datos para ciertos propósitos</li>
                            <li><strong>Retirar consentimiento:</strong> Retirar su consentimiento en cualquier momento</li>
                        </ul>
                        <p class="mt-3">
                            Para ejercer estos derechos, puede contactarnos a través de los medios indicados en la
                            sección "Contacto" de esta política.
                        </p>
                    </section>

                    <section>
                        <h2 id="cookies" class="text-2xl font-bold mb-4 text-white">9. Cookies y Tecnologías de Seguimiento</h2>
                        <p>
                            Utilizamos cookies y tecnologías similares para mejorar su experiencia en nuestra plataforma:
                        </p>
                        <ul class="list-disc pl-6 space-y-2">
                            <li><strong>Cookies de sesión:</strong> Necesarias para mantener su sesión activa y segura</li>
                            <li><strong>Cookies de funcionalidad:</strong> Para recordar sus preferencias y configuraciones</li>
                            <li><strong>Cookies de seguridad:</strong> Para proteger contra fraudes y accesos no autorizados</li>
                            <li><strong>Cookies analíticas:</strong> Para entender cómo se usa la plataforma y mejorar el servicio</li>
                        </ul>
                        <p class="mt-3">
                            Puede configurar su navegador para rechazar cookies, pero esto puede afectar la funcionalidad
                            de la plataforma.
                        </p>
                    </section>

                    <section>
                        <h2 id="transferencias" class="text-2xl font-bold mb-4 text-white">10. Transferencias Internacionales de Datos</h2>
                        <p>
                            Sus datos pueden ser transferidos y almacenados en servidores ubicados fuera de su país de residencia.
                            Cuando realicemos transferencias internacionales, implementaremos medidas apropiadas para garantizar
                            que sus datos reciban un nivel de protección adecuado conforme a esta política y las leyes aplicables.
                        </p>
                    </section>

                    <section>
                        <h2 id="menores" class="text-2xl font-bold mb-4 text-white">11. Privacidad de Menores</h2>
                        <p>
                            Nuestros servicios están dirigidos a empresas y usuarios profesionales. No recopilamos
                            conscientemente información personal de menores de 18 años. Si descubrimos que hemos recopilado
                            datos de un menor sin consentimiento parental adecuado, tomaremos medidas para eliminar esa
                            información de nuestros sistemas.
                        </p>
                    </section>

                    <section>
                        <h2 id="cambios" class="text-2xl font-bold mb-4 text-white">12. Cambios a esta Política de Privacidad</h2>
                        <p>
                            Podemos actualizar esta Política de Privacidad ocasionalmente para reflejar cambios en nuestras
                            prácticas o por razones operativas, legales o regulatorias. Cuando realicemos cambios significativos,
                            le notificaremos por correo electrónico o mediante un aviso destacado en la plataforma antes de que
                            los cambios entren en vigor.
                        </p>
                        <p class="mt-3">
                            Le recomendamos revisar esta política periódicamente para mantenerse informado sobre cómo
                            protegemos su información.
                        </p>
                    </section>

                    <section>
                        <h2 id="contacto" class="text-2xl font-bold mb-4 text-white">13. Contacto</h2>
                        <p>
                            Si tiene preguntas, inquietudes o solicitudes relacionadas con esta Política de Privacidad o
                            nuestras prácticas de datos, puede contactarnos:
                        </p>
                        <div class="bg-gray-800/80 border border-gray-600 p-4 rounded-lg mt-3">
                            <p><strong>Gestior - Plataforma de Gestión Empresarial</strong></p>
                            <p class="mt-2">
                                <strong>Correo electrónico:</strong>
                                <a href="mailto:privacy@gestior.com" class="text-purple-400 hover:text-purple-300 underline transition-colors">
                                    privacy@gestior.com
                                </a>
                            </p>
                            <p class="mt-1">
                                <strong>Soporte:</strong>
                                <a href="{{ route('contact') }}" class="text-purple-400 hover:text-purple-300 underline transition-colors">
                                    Formulario de contacto
                                </a>
                            </p>
                        </div>
                    </section>

                    <section>
                        <h2 id="consentimiento" class="text-2xl font-bold mb-4 text-white">14. Consentimiento</h2>
                        <p>
                            Al utilizar nuestros servicios, usted reconoce que ha leído y comprendido esta Política de
                            Privacidad y consiente el procesamiento de sus datos personales según lo descrito en este documento.
                        </p>
                    </section>

                    <div class="mt-12 pt-8 border-t border-gray-800">
                        <p class="text-sm text-gray-500 text-center">
                            Esta Política de Privacidad fue actualizada por última vez el {{ date('d/m/Y') }} y es efectiva a partir de esta fecha.
                        </p>
                    </div>
                    <div class="mt-10 text-center">
                        <a href="#introduccion" class="inline-flex items-center gap-2 px-6 py-2 bg-white/10 hover:bg-white/20 border border-white/10 text-white rounded-lg transition-colors">
                            Volver al inicio de la política
                        </a>
                    </div>
                </div>
            </div>

            <!-- Botón de regreso -->
            <div class="mt-10 text-center">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-8 py-3 bg-white text-black font-semibold rounded-lg hover:bg-gray-200 transition-all duration-200 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Volver al inicio
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
