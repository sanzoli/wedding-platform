export interface Guest {
    id: number;
    first_name: ?string;
    last_name: ?string;
    mobile: ?string;
    lang: 'en' | 'es' | 'pt' | null;
}

export interface GuestGroup {
    id: number;
    count: number;
    primary: Guest;
    companies: Guest[];
}
