export interface Guest {
    id: number;
    full_name: string;
    first_name: string;
    last_name: string;
    initials: string;
    mobile: string;
    lang: 'en' | 'es' | 'pt' | '';
    flag: string;
}

export interface GuestGroup {
    id: number;
    count: number;
    primary: Guest;
    companies: Guest[];
}
