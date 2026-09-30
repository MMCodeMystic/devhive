// Cards Module - Homepage and Project Cards with Video Hover Support

document.addEventListener('DOMContentLoaded', () => {
    // Handle click on cards with data-src attribute (project iframes)
    const projectCards = document.querySelectorAll('.card.project-card[data-src]');
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
    const navCards = document.querySelectorAll('.card:not(.project-card):not(.video-card):not([data-src])');
    navCards.forEach(card => {
        card.addEventListener('click', () => {
            const link = card.querySelector('a.btn');
            if (link) {
                window.location.href = link.href;
            }
        });
    });

    // Video Hover Play/Pause Logic
    const videoCards = document.querySelectorAll('.video-card');
    videoCards.forEach(card => {
        const video = card.querySelector('.project-video');
        if (!video) return;

        let wasPlaying = false;
        let lastTime = 0;

        // Store video state on mouse leave
        card.addEventListener('mouseleave', () => {
            if (!video.paused) {
                wasPlaying = true;
                lastTime = video.currentTime;
                video.pause();
            }
        });

        // Resume from last position on mouse enter
        card.addEventListener('mouseenter', () => {
            if (wasPlaying) {
                video.currentTime = lastTime;
                video.play().catch(e => console.log('Video play error:', e));
            } else {
                // First hover - play from beginning
                video.currentTime = 0;
                video.play().catch(e => console.log('Video play error:', e));
            }
        });

        // Pause when video ends
        video.addEventListener('ended', () => {
            video.currentTime = 0;
            wasPlaying = false;
        });

        // Handle initial click to start video
        card.addEventListener('click', () => {
            if (video.paused) {
                video.play().catch(e => console.log('Video play error:', e));
            } else {
                video.pause();
            }
        });
    });
});
