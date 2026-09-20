import type { Guest } from './guests';

export type ResponseOption = 'yes' | 'probably_yes' | 'probably_no' | 'no';

export interface Invitation {
    id: string;
    guest: {
        id: number;
        name: string;
    };
    response: ResponseOption | null;
}

export interface Language {
    label: string;
    value: string;
    flag: string;
}

export interface SaveTheDateProps {
    id: string;
    currentGuest: Guest;
    invitations: {
        data: Invitation[];
        total: number;
        answered: number;
    };
    language: string;
    coupleNames: string;
    date: string;
    location: string;
    languages: Record<string, Language>;
    options: Record<ResponseOption, string>;
}
