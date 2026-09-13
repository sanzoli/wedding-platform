import type { Guest } from './guests';

export type ResponseOption = 'yes' | 'probably_yes' | 'probably_no' | 'no';

export type SaveStatus = 'saving' | 'saved' | 'error';

export interface GuestGroupMember extends Guest {
    response: ResponseOption | null;
}

export interface Language {
    label: string;
    value: string;
    flag: string;
}

export interface SaveTheDateProps {
    currentGuest: Guest;
    guestGroup: GuestGroupMember[];
    lang: string;
    languages: Record<string, Language>;
    options: Record<ResponseOption, string>;
}
