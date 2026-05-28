export type GuestLanguage = 'es' | 'en' | 'pt';

export interface PrimaryGuestView {
    id: string;
    name: string;
    surname: string;
    mobile: string | null;
    language: GuestLanguage;
}

export interface CompanionView {
    id: string;
    name: string;
    surname: string;
    mobile: string | null;
    language: GuestLanguage;
    isPending: boolean;
}

export interface GuestGroupView {
    id: string;
    primary: PrimaryGuestView;
    companions: CompanionView[];
    totalMembers: number;
    pendingCompanions: number;
    needsAttention: boolean;
}
