(function(){
    const STORAGE_KEY = 'ozone_theme';

    function applyTheme(theme){
        if(theme === 'dark') document.documentElement.setAttribute('data-theme','dark');
        else document.documentElement.removeAttribute('data-theme');
    }

    function toggleTheme(){
        const current = localStorage.getItem(STORAGE_KEY) || 'light';
        const next = current === 'dark' ? 'light' : 'dark';
        localStorage.setItem(STORAGE_KEY, next);
        applyTheme(next);
        updateButton(next);
    }

    function updateButton(theme){
        const btn = document.getElementById('theme-toggle-btn');
        if(!btn) return;
        btn.setAttribute('aria-pressed', theme === 'dark');
        btn.querySelector('.dot').style.background = theme === 'dark' ? getComputedStyle(document.documentElement).getPropertyValue('--accent') : getComputedStyle(document.documentElement).getPropertyValue('--brand-primary') || '#3b82f6';
        btn.querySelector('.label').textContent = theme === 'dark' ? 'Night' : 'Day';
    }

    function ensureButton(){
        const selectors = [
            '.header-inner',
            '.site-header .container',
            '.dash-header',
            '.dash-header .admin-profile',
        ];

        selectors.forEach(sel => {
            const nodes = document.querySelectorAll(sel);
            nodes.forEach(h => {
                if (h.querySelector && h.querySelector('#theme-toggle-btn')) return;
                const btn = document.createElement('button');
                btn.id = 'theme-toggle-btn';
                btn.className = 'theme-toggle';
                btn.type = 'button';
                btn.setAttribute('aria-pressed', 'false');
                btn.innerHTML = '<span class="dot"></span><span class="label">Day</span>';
                btn.addEventListener('click', toggleTheme);

                // For dashboard header, place before admin-profile when possible
                if (h.classList && h.classList.contains('dash-header')) {
                    const profile = h.querySelector('.admin-profile');
                    if (profile) {
                        profile.parentNode.insertBefore(btn, profile);
                        return;
                    }
                }

                // For admin-profile container, append inside it
                if (h.classList && h.classList.contains('admin-profile')) {
                    h.appendChild(btn);
                    return;
                }

                // Default: append at end
                h.appendChild(btn);
            });
        });
    }

    // init
    const saved = localStorage.getItem(STORAGE_KEY) || 'light';
    applyTheme(saved);

    // wait for DOM
    if(document.readyState === 'loading'){
        document.addEventListener('DOMContentLoaded', function(){
            ensureButton();
            updateButton(saved);
        });
    } else {
        ensureButton();
        updateButton(saved);
    }

})();
