// Cards Module - Homepage and Project Cards

document.addEventListener('DOMContentLoaded', () => {
    // Handle click on cards with data-src attribute (project iframes)
    const projectCards = document.querySelectorAll('.card[data-src]');
    projectCards.forEach(card => {
        card.addEventListener('click', () => {
            if (card.classList.contains('loaded')) return;
            
            const iframe = document.createElement('iframe');
            iframe.src = card.dataset.src;
            iframe.style.width = '100%';
            iframe.style.height = '250px';
            iframe.style.border = 'none';
            iframe.style.position = 'absolute';
            iframe.style.top = '0';
            iframe.style.left = '0';
            iframe.style.background = 'var(--nav-bg)';
            iframe.style.zIndex = '1';
            
            card.appendChild(iframe);
            card.classList.add('loaded');
        });
    });

    // Handle click on regular cards (navigation)
    const navCards = document.querySelectorAll('.card:not([data-src])');
    navCards.forEach(card => {
        card.addEventListener('click', () => {
            const link = card.querySelector('a.btn');
            if (link) {
                window.location.href = link.href;
            }
        });
    });
});
