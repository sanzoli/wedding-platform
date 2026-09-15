<template>
    <svg
        class="guest-envelope-sheet"
        viewBox="0 0 1200 800"
        preserveAspectRatio="xMidYMid meet"
        aria-hidden="true"
    >
        <defs>
            <linearGradient id="envelope-face" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0" stop-color="hsl(40 45% 96.5%)" />
                <stop offset="1" stop-color="hsl(39 32% 93.8%)" />
            </linearGradient>
            <linearGradient
                id="envelope-flap-bottom"
                x1="0"
                y1="1"
                x2="0"
                y2="0"
            >
                <stop offset="0" stop-color="hsl(42 36% 95.2%)" />
                <stop offset="1" stop-color="hsl(42 28% 92.6%)" />
            </linearGradient>
            <linearGradient
                id="envelope-flap-left"
                x1="0"
                y1="0"
                x2="1"
                y2="0"
            >
                <stop offset="0" stop-color="hsl(41 34% 94.7%)" />
                <stop offset="1" stop-color="hsl(42 27% 92.3%)" />
            </linearGradient>
            <linearGradient
                id="envelope-flap-right"
                x1="1"
                y1="0"
                x2="0"
                y2="0"
            >
                <stop offset="0" stop-color="hsl(41 34% 94.7%)" />
                <stop offset="1" stop-color="hsl(42 27% 92.3%)" />
            </linearGradient>
            <radialGradient id="envelope-pocket" cx="50%" cy="12%" r="90%">
                <stop offset="0" stop-color="hsl(40 24% 90.3%)" />
                <stop offset="55%" stop-color="hsl(40 26% 91.7%)" />
                <stop offset="100%" stop-color="hsl(40 28% 92.9%)" />
            </radialGradient>

            <filter id="envelope-mottle" x="0" y="0" width="100%" height="100%">
                <feTurbulence
                    type="fractalNoise"
                    baseFrequency="0.012 0.024"
                    numOctaves="4"
                    seed="6"
                    result="n"
                />
                <feColorMatrix
                    in="n"
                    type="matrix"
                    values="0 0 0 0 0.32  0 0 0 0 0.28  0 0 0 0 0.22  0 0 0 0.06 0"
                />
            </filter>
            <filter id="envelope-fibre" x="0" y="0" width="100%" height="100%">
                <feTurbulence
                    type="fractalNoise"
                    baseFrequency="0.006 0.09"
                    numOctaves="2"
                    seed="21"
                    result="n"
                />
                <feColorMatrix
                    in="n"
                    type="matrix"
                    values="0 0 0 0 0.30  0 0 0 0 0.27  0 0 0 0 0.22  0 0 0 0.03 0"
                />
            </filter>
            <radialGradient id="envelope-age" cx="50%" cy="45%" r="72%">
                <stop offset="58%" stop-color="#3a2c12" stop-opacity="0" />
                <stop offset="100%" stop-color="#3a2c12" stop-opacity=".045" />
            </radialGradient>
            <clipPath id="envelope-clip">
                <rect x="0" y="0" width="1200" height="800" rx="16" />
            </clipPath>
        </defs>

        <rect
            x="0"
            y="0"
            width="1200"
            height="800"
            rx="16"
            fill="url(#envelope-face)"
        />

        <path d="M0 0 L1200 0 L600 430 Z" fill="url(#envelope-pocket)" />
        <path
            d="M0 0 L1200 0 L600 430 Z"
            fill="none"
            stroke="rgba(70,48,22,.075)"
            stroke-width="26"
            stroke-linejoin="round"
            opacity=".5"
        />

        <path d="M0 800 L1200 800 L600 430 Z" fill="url(#envelope-flap-bottom)" />
        <path
            d="M0 800 L600 430 L1200 800"
            fill="none"
            stroke="rgba(120,92,50,.18)"
            stroke-width="1.5"
        />
        <path d="M0 0 L0 800 L600 430 Z" fill="url(#envelope-flap-left)" />
        <path
            d="M0 0 L600 430 L0 800"
            fill="none"
            stroke="rgba(120,92,50,.14)"
            stroke-width="1.5"
        />
        <path d="M1200 0 L1200 800 L600 430 Z" fill="url(#envelope-flap-right)" />
        <path
            d="M1200 0 L600 430 L1200 800"
            fill="none"
            stroke="rgba(120,92,50,.14)"
            stroke-width="1.5"
        />

        <g clip-path="url(#envelope-clip)">
            <rect
                x="0"
                y="0"
                width="1200"
                height="800"
                filter="url(#envelope-mottle)"
                style="mix-blend-mode: multiply"
            />
            <rect
                x="0"
                y="0"
                width="1200"
                height="800"
                filter="url(#envelope-fibre)"
                style="mix-blend-mode: multiply"
            />
            <rect x="0" y="0" width="1200" height="800" fill="url(#envelope-age)" />
        </g>

        <rect
            x="1"
            y="1"
            width="1198"
            height="798"
            rx="16"
            fill="none"
            stroke="var(--guest-parchment-edge)"
            stroke-width="2"
        />
    </svg>

    <svg
        class="guest-envelope-sheet guest-envelope-flap"
        viewBox="0 0 1200 800"
        preserveAspectRatio="xMidYMid meet"
        aria-hidden="true"
    >
        <defs>
            <linearGradient id="envelope-flap-top" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0" stop-color="hsl(40 45% 96.7%)" />
                <stop offset="1" stop-color="hsl(40 34% 94%)" />
            </linearGradient>
            <clipPath id="envelope-flap-clip">
                <path d="M0 0 L1200 0 L600 430 Z" />
            </clipPath>
        </defs>

        <path d="M0 0 L1200 0 L600 430 Z" fill="url(#envelope-flap-top)" />
        <g clip-path="url(#envelope-flap-clip)">
            <rect
                x="0"
                y="0"
                width="1200"
                height="440"
                filter="url(#envelope-mottle)"
                style="mix-blend-mode: multiply"
            />
            <rect
                x="0"
                y="0"
                width="1200"
                height="440"
                filter="url(#envelope-fibre)"
                style="mix-blend-mode: multiply"
            />
        </g>
        <path
            d="M0 0 L600 430 L1200 0"
            fill="none"
            stroke="rgba(120,92,50,.16)"
            stroke-width="1.5"
        />
        <path d="M0 0 L600 430" stroke="rgba(255,255,255,.45)" stroke-width="1" />
        <path d="M1200 0 L600 430" stroke="rgba(255,255,255,.45)" stroke-width="1" />
    </svg>
</template>
