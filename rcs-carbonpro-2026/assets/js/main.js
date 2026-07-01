        function toggleTheme() {
            document.body.classList.toggle('light-mode');
            const isLight = document.body.classList.contains('light-mode');
            
            // Switch icone luna/sole
            const moonIcon = document.querySelector('.icon-moon');
            const sunIcon = document.querySelector('.icon-sun');
            
            if (isLight) {
                moonIcon.style.display = 'none';
                sunIcon.style.display = 'block';
            } else {
                moonIcon.style.display = 'block';
                sunIcon.style.display = 'none';
            }
            
            localStorage.setItem('theme', isLight ? 'light' : 'dark');
        }
        
        function toggleMobileMenu() {
            const mobileMenu = document.querySelector('.mobile-menu');
            const hamburger = document.querySelector('.hamburger');
            
            mobileMenu.classList.toggle('active');
            hamburger.classList.toggle('active');
        }
        
        // Load saved theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme');
            const moonIcon = document.querySelector('.icon-moon');
            const sunIcon = document.querySelector('.icon-sun');
            
            if (savedTheme === 'light') {
                document.body.classList.add('light-mode');
                if (moonIcon) moonIcon.style.display = 'none';
                if (sunIcon) sunIcon.style.display = 'block';
            } else {
                if (moonIcon) moonIcon.style.display = 'block';
                if (sunIcon) sunIcon.style.display = 'none';
            }
            
            // Gestione link menu attivi
            const navLinks = document.querySelectorAll('.nav-links a');
            const currentPage = window.location.pathname;
            
            navLinks.forEach(link => {
                // Rimuovi active da tutti
                link.classList.remove('active');
                
                // Aggiungi active al link corrente
                if (link.getAttribute('href') === currentPage || 
                    (currentPage === '/' && link.getAttribute('href') === '/')) {
                    link.classList.add('active');
                }
                
                // Click handler per mantenere lo stato
                link.addEventListener('click', function() {
                    navLinks.forEach(l => l.classList.remove('active'));
                    this.classList.add('active');
                });
            });
        });
