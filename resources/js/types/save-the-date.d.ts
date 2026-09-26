import { Language } from '@/types/index';
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

export interface SaveTheDateProps {
    id: string;
    currentGuest: Guest;
    invitations: {
        data: Invitation[];
        total: number;
        answered: number;
    };
    language: Language;
    coupleNames: string;
    date: string;
    location: string;
}
