<template>
    <svg class="absolute size-0" aria-hidden="true" focusable="false">
        <!-- Deckle edge: grain displaces the card's ::before, ground and shadow only -->
        <filter id="guest-deckle" x="-10%" y="-10%" width="120%" height="120%">
            <feTurbulence
                type="fractalNoise"
                baseFrequency="0.022 0.022"
                numOctaves="3"
                seed="11"
                result="grain"
            />
            <feDisplacementMap
                in="SourceGraphic"
                in2="grain"
                scale="6"
                xChannelSelector="R"
                yChannelSelector="G"
            />
        </filter>
    </svg>
</template>
