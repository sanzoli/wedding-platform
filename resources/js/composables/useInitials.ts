export function getInitials(...words: string[]): string {
    let initials = '';

    for (const word of words) {
        if ( word.length ) {
            initials += word.at(0)?.toUpperCase()
        }
    }

    return initials.length ? initials : '?';
}

export function useInitials() {
    return { getInitials };
}
