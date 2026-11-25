document.addEventListener('DOMContentLoaded', function () {

    const festivalThemes = [

        // 1. Royal Blue + Purple
        {
            colors: ['#061329', '#17265c', '#35205f', '#542477'],
            glow: [
                'rgba(145, 65, 220, 0.42)',
                'rgba(105, 45, 190, 0.38)',
                'rgba(75, 45, 150, 0.25)'
            ],
            rupee: 'rgba(255, 215, 100, 0.35)'
        },

        // 2. Emerald Green + Gold
        {
            colors: ['#03251d', '#075441', '#08765a', '#123e35'],
            glow: [
                'rgba(35, 220, 145, 0.38)',
                'rgba(10, 150, 110, 0.35)',
                'rgba(20, 100, 75, 0.25)'
            ],
            rupee: 'rgba(255, 220, 100, 0.40)'
        },

        // 3. Burgundy + Rose Gold
        {
            colors: ['#260b18', '#50132d', '#792342', '#35142e'],
            glow: [
                'rgba(220, 70, 120, 0.38)',
                'rgba(170, 45, 90, 0.35)',
                'rgba(120, 35, 75, 0.25)'
            ],
            rupee: 'rgba(255, 195, 130, 0.40)'
        },

        // 4. Midnight Blue + Cyan
        {
            colors: ['#031426', '#073653', '#07516a', '#102c50'],
            glow: [
                'rgba(30, 180, 220, 0.38)',
                'rgba(20, 120, 190, 0.35)',
                'rgba(20, 80, 140, 0.25)'
            ],
            rupee: 'rgba(120, 230, 255, 0.40)'
        },

        // 5. Dark Gold + Bronze
        {
            colors: ['#211605', '#49300b', '#725018', '#34220b'],
            glow: [
                'rgba(220, 165, 45, 0.38)',
                'rgba(180, 115, 25, 0.35)',
                'rgba(120, 80, 15, 0.25)'
            ],
            rupee: 'rgba(255, 225, 130, 0.45)'
        }
    ];

    let currentTheme = 0;

    function applyFestivalTheme(index) {

        const theme = festivalThemes[index];
        const root = document.documentElement;

        root.style.setProperty('--aj-festival-color-1', theme.colors[0]);
        root.style.setProperty('--aj-festival-color-2', theme.colors[1]);
        root.style.setProperty('--aj-festival-color-3', theme.colors[2]);
        root.style.setProperty('--aj-festival-color-4', theme.colors[3]);

        root.style.setProperty('--aj-festival-glow-1', theme.glow[0]);
        root.style.setProperty('--aj-festival-glow-2', theme.glow[1]);
        root.style.setProperty('--aj-festival-glow-3', theme.glow[2]);

        root.style.setProperty('--aj-festival-rupee', theme.rupee);
    }

    // Start with the first theme.
    applyFestivalTheme(currentTheme);

    // Change the theme every 10 minutes.
    setInterval(function () {

        currentTheme = (currentTheme + 1) % festivalThemes.length;

        applyFestivalTheme(currentTheme);

    }, 1 * 60 * 1000);

});