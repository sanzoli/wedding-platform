/**
 * Mock data for the guests admin demo branch.
 * Public exports: mockGuestGroupViews, mockTotalGuests, mockTotalGroups.
 * The raw arrays and the composition helper stay private so consumers
 * can't recreate backend-shaped logic on the frontend; the page imports
 * the precomputed views directly.
 */

import type {
    CompanionView,
    GuestGroupView,
    GuestLanguage,
    PrimaryGuestView,
} from '@/types/guests';

type NameStatus = 'known' | 'pending';

interface RawGuest {
    id: string;
    name: string;
    surname: string;
    mobile: string | null;
    language: GuestLanguage;
    guestGroupId: string;
    isPrimary: boolean;
    nameStatus: NameStatus;
    archivedAt: string | null;
}

interface RawGuestGroup {
    id: string;
    primaryGuestId: string;
}

function guest(
    id: string,
    groupId: string,
    isPrimary: boolean,
    name: string,
    surname: string,
    language: GuestLanguage,
    mobile: string | null = null,
    nameStatus: NameStatus = 'known',
): RawGuest {
    return {
        id,
        name,
        surname,
        mobile,
        language,
        guestGroupId: groupId,
        isPrimary,
        nameStatus,
        archivedAt: null,
    };
}

function group(id: string, primaryGuestId: string): RawGuestGroup {
    return { id, primaryGuestId };
}

// ---------------------------------------------------------------------------
// 14 groups · 50 guests · 4 with pending companions ("necesitan atención").
// A couple of companions carry a mobile to exercise the companion mobile
// column; missing companion mobile never triggers the attention warning.
// ---------------------------------------------------------------------------

const mockGuests: RawGuest[] = [
    // 1. María Gómez — singleton ES
    guest('g_001', 'gg_01', true, 'María', 'Gómez', 'es', '+57 300 123 4567'),

    // 2. Sarah & James Mitchell — couple EN (companion has a mobile)
    guest('g_002', 'gg_02', true, 'Sarah', 'Mitchell', 'en', '+1 555 234 5678'),
    guest('g_003', 'gg_02', false, 'James', 'Mitchell', 'en', '+1 555 888 1212'),

    // 3. Ricardo Oliveira family — 5, PT, 1 pending → needs attention
    guest('g_004', 'gg_03', true, 'Ricardo', 'Oliveira', 'pt', '+55 21 98765 4321'),
    guest('g_005', 'gg_03', false, 'Beatriz', 'Oliveira', 'pt'),
    guest('g_006', 'gg_03', false, 'Lucas', 'Oliveira', 'pt'),
    guest('g_007', 'gg_03', false, 'Sofia', 'Oliveira', 'pt'),
    guest('g_008', 'gg_03', false, '', '', 'pt', null, 'pending'),

    // 4. Carlos Pérez — singleton ES
    guest('g_009', 'gg_04', true, 'Carlos', 'Pérez', 'es', '+57 311 882 0019'),

    // 5. Camila & Mateo Restrepo — couple ES (companion has a mobile)
    guest('g_010', 'gg_05', true, 'Camila', 'Restrepo', 'es', '+57 320 444 8800'),
    guest('g_011', 'gg_05', false, 'Mateo', 'Restrepo', 'es', '+57 322 111 3344'),

    // 6. Familia Costa — 7, PT
    guest('g_012', 'gg_06', true, 'Pedro', 'Costa', 'pt', '+55 11 91234 5678'),
    guest('g_013', 'gg_06', false, 'Helena', 'Costa', 'pt'),
    guest('g_014', 'gg_06', false, 'Tiago', 'Costa', 'pt'),
    guest('g_015', 'gg_06', false, 'Mariana', 'Costa', 'pt'),
    guest('g_016', 'gg_06', false, 'Rafael', 'Costa', 'pt'),
    guest('g_017', 'gg_06', false, 'Isabel', 'Costa', 'pt'),
    guest('g_018', 'gg_06', false, 'Diego', 'Costa', 'pt'),

    // 7. Familia López — 6, ES
    guest('g_019', 'gg_07', true, 'Andrés', 'López', 'es', '+57 301 555 9100'),
    guest('g_020', 'gg_07', false, 'Valentina', 'López', 'es'),
    guest('g_021', 'gg_07', false, 'Daniel', 'López', 'es'),
    guest('g_022', 'gg_07', false, 'Isabella', 'López', 'es'),
    guest('g_023', 'gg_07', false, 'Tomás', 'López', 'es'),
    guest('g_024', 'gg_07', false, 'Luciana', 'López', 'es'),

    // 8. Familia Hernández — 5, ES, 1 pending → needs attention
    guest('g_025', 'gg_08', true, 'Felipe', 'Hernández', 'es', '+57 315 222 7711'),
    guest('g_026', 'gg_08', false, 'Paola', 'Hernández', 'es'),
    guest('g_027', 'gg_08', false, 'Santiago', 'Hernández', 'es'),
    guest('g_028', 'gg_08', false, 'Manuela', 'Hernández', 'es'),
    guest('g_029', 'gg_08', false, '', '', 'es', null, 'pending'),

    // 9. Familia García — 5, ES
    guest('g_030', 'gg_09', true, 'Laura', 'García', 'es', '+57 318 644 2200'),
    guest('g_031', 'gg_09', false, 'Alejandro', 'García', 'es'),
    guest('g_032', 'gg_09', false, 'Camilo', 'García', 'es'),
    guest('g_033', 'gg_09', false, 'Sara', 'García', 'es'),
    guest('g_034', 'gg_09', false, 'Nicolás', 'García', 'es'),

    // 10. Familia Almeida — 4, PT
    guest('g_035', 'gg_10', true, 'João', 'Almeida', 'pt', '+55 31 99876 1122'),
    guest('g_036', 'gg_10', false, 'Beatriz', 'Almeida', 'pt'),
    guest('g_037', 'gg_10', false, 'Henrique', 'Almeida', 'pt'),
    guest('g_038', 'gg_10', false, 'Laura', 'Almeida', 'pt'),

    // 11. Familia Torres — 4, ES, primary without mobile → needs attention
    guest('g_039', 'gg_11', true, 'Diego', 'Torres', 'es', null),
    guest('g_040', 'gg_11', false, 'Antonia', 'Torres', 'es'),
    guest('g_041', 'gg_11', false, 'Martín', 'Torres', 'es'),
    guest('g_042', 'gg_11', false, 'Sebastián', 'Torres', 'es'),

    // 12. Williams family — 3, EN
    guest('g_043', 'gg_12', true, 'Emily', 'Williams', 'en', '+1 415 998 0033'),
    guest('g_044', 'gg_12', false, 'Michael', 'Williams', 'en'),
    guest('g_045', 'gg_12', false, 'Olivia', 'Williams', 'en'),

    // 13. Familia Vargas — 3, ES, 1 pending → needs attention
    guest('g_046', 'gg_13', true, 'Juliana', 'Vargas', 'es', '+57 304 711 5566'),
    guest('g_047', 'gg_13', false, 'Esteban', 'Vargas', 'es'),
    guest('g_048', 'gg_13', false, '', '', 'es', null, 'pending'),

    // 14. Familia Souza — 2, PT
    guest('g_049', 'gg_14', true, 'Carolina', 'Souza', 'pt', '+55 41 92211 7788'),
    guest('g_050', 'gg_14', false, 'Bruno', 'Souza', 'pt'),
];

const mockGuestGroups: RawGuestGroup[] = [
    group('gg_01', 'g_001'),
    group('gg_02', 'g_002'),
    group('gg_03', 'g_004'),
    group('gg_04', 'g_009'),
    group('gg_05', 'g_010'),
    group('gg_06', 'g_012'),
    group('gg_07', 'g_019'),
    group('gg_08', 'g_025'),
    group('gg_09', 'g_030'),
    group('gg_10', 'g_035'),
    group('gg_11', 'g_039'),
    group('gg_12', 'g_043'),
    group('gg_13', 'g_046'),
    group('gg_14', 'g_049'),
];

function toPrimaryView(raw: RawGuest): PrimaryGuestView {
    return {
        id: raw.id,
        name: raw.name,
        surname: raw.surname,
        mobile: raw.mobile,
        language: raw.language,
    };
}

function toCompanionView(raw: RawGuest): CompanionView {
    return {
        id: raw.id,
        name: raw.name,
        surname: raw.surname,
        mobile: raw.mobile,
        language: raw.language,
        isPending: raw.nameStatus === 'pending',
    };
}

function buildGuestGroupViews(
    groups: RawGuestGroup[],
    guests: RawGuest[],
): GuestGroupView[] {
    const active = guests.filter((g) => g.archivedAt === null);

    return groups.map((grp) => {
        const members = active.filter((g) => g.guestGroupId === grp.id);
        const primary = members.find((g) => g.id === grp.primaryGuestId);

        if (!primary) {
            throw new Error(
                `[guests mock] group ${grp.id} has no active primary guest`,
            );
        }

        const companions = members.filter((g) => !g.isPrimary);
        const pendingCompanions = companions.filter(
            (g) => g.nameStatus === 'pending',
        ).length;

        return {
            id: grp.id,
            primary: toPrimaryView(primary),
            companions: companions.map(toCompanionView),
            totalMembers: members.length,
            pendingCompanions,
            needsAttention: pendingCompanions > 0 || !primary.mobile?.trim(),
        };
    });
}

export const mockGuestGroupViews: GuestGroupView[] = buildGuestGroupViews(
    mockGuestGroups,
    mockGuests,
);

export const mockTotalGuests: number = mockGuests.filter(
    (g) => g.archivedAt === null,
).length;

export const mockTotalGroups: number = mockGuestGroups.length;
