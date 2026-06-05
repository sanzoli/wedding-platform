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

export interface SelectGroupOption {
    groupId: string;
    name: string;
    surname: string;
}

export type SelectGroupDialogStatus =
    | 'idle'
    | 'submitting'
    | 'success'
    | 'error';

export interface SelectGroupSource {
    guestId: string;
    name: string;
    surname: string;
}