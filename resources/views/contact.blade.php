<x-guest-layout>
    <section class="relative bg-black text-white py-24">
        <style>
            .contact-container { max-width: 880px; margin: 0 auto; padding: 0 2rem; }
            .contact-title { font-size: clamp(2rem, 5vw, 3rem); font-weight: 700; letter-spacing: -0.03em; margin-bottom: 0.75rem; }
            .contact-subtitle { color: rgba(255,255,255,0.6); font-size: 1.1rem; margin-bottom: 2rem; }
            .contact-card { background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 1.5rem; display: flex; align-items: center; gap: 0.75rem; }
            .contact-link { color: rgba(255,255,255,0.85); text-decoration: none; }
            .contact-link:hover { text-decoration: underline; }
        </style>
        <div class="contact-container">
            <h1 class="contact-title">Contacto</h1>
            <p class="contact-subtitle">Escríbenos y te respondemos a la brevedad.</p>

            <div class="contact-card" style="margin-bottom: 1rem;">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8" width="22" height="22">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                </svg>
                <a class="contact-link" href="mailto:soporte@gestior.com.ar">soporte@gestior.com.ar</a>
            </div>

            <div class="contact-card" style="gap: 0.75rem;">
                <svg fill="currentColor" viewBox="0 0 24 24" width="22" height="22" aria-hidden="true">
                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                </svg>
                <a class="contact-link" href="https://instagram.com/gestior" target="_blank" rel="noopener noreferrer">@gestior</a>
            </div>
        </div>
    </section>
</x-guest-layout>
