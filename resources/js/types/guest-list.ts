/**
 * Domain types for the guest-list feature.
 *
 * The Guest / GuestGroup shapes mirror the schema proposed for the backend
 * (work-in-progress in another branch).
 */

export type GuestNameStatus = 'known' | 'pending';

export type GuestLanguage = 'es' | 'en' | 'pt';

export interface Guest {
    id: string;
    name: string;
    surname: string;
    mobile: string | null;
    language: GuestLanguage;
    guest_group_id: string;
    is_primary: boolean;
    name_status: GuestNameStatus;
    archived_at: string | null;
    created_at: string;
    updated_at: string;
}

export interface GuestGroup {
    id: string;
    wedding_id: string;
    primary_guest_id: string;
    created_at: string;
    updated_at: string;
}

/**
 * Composed view model the table consumes. The page derives it from
 * Guest[] + GuestGroup[] so backend integration only swaps the source.
 */
export interface GuestGroupView {
    id: string;
    primary: Guest;
    companions: Guest[];
    totalMembers: number;
    confirmedCompanions: number;
    pendingCompanions: number;
    needsAttention: boolean;
}
