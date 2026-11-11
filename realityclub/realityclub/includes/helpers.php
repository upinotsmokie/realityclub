<?php
require_once __DIR__ . '/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

function e($v): string
{
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

function rupiah(int $n): string
{
    return 'Rp' . number_format($n, 0, ',', '.');
}

function rupiah_short(int $n): string
{
    return ($n >= 1000 && $n % 1000 === 0) ? 'Rp' . ($n / 1000) . 'K' : rupiah($n);
}

function album_label(array $a): string
{
    $label = $a['release_year'] . ' · ' . $a['album_type'];
    if (!empty($a['award'])) {
        $label .= ' · ' . (strpos($a['award'], 'AMI') === 0 ? 'AMI Award' : $a['award']);
    }
    if (!empty($a['is_debut'])) {
        $label .= ' · Debut';
    }
    return $label;
}

// Mencetak kartu jadwal konser (dipakai di index.php dan tour.php)
function render_tour_cards(array $tours, bool $home = false): void
{
    $labels = [
        'available' => ['Tersedia', ''],
        'limited'   => ['Tiket Terbatas', 'limited'],
        'soldout'   => ['Sold Out', 'soldout'],
    ];
    foreach ($tours as $i => $t) {
        $ts      = strtotime($t['show_date']);
        $title   = $t['city'] . ' — ' . $t['venue'];
        $full    = $title . ', ' . date('j M Y', $ts);
        $info    = $labels[$t['status']] ?? $labels['available'];
        $soldout = $t['status'] === 'soldout';
        $delay   = $i ? ' delay-' . min($i, 4) : '';
        $btnCls  = $soldout ? 'btn-outline' : 'btn-primary';
        $btnText = $soldout ? 'Waitlist' : 'Beli Tiket';

        echo '<div class="tour-card reveal' . $delay . '">';
        echo '<div class="tour-date"><span class="d">' . date('d', $ts) . '</span><span class="m">' . strtoupper(date('M', $ts)) . '</span></div>';
        echo '<div class="tour-info"><h3>' . e($title) . '</h3><p class="venue">' . e($t['tour_name']) . '</p></div>';
        echo '<span class="tour-status ' . $info[1] . '">' . $info[0] . '</span>';
        if ($home) {
            echo '<a class="btn ' . $btnCls . ' tour-buy" href="tour.php">' . $btnText . '</a>';
        } else {
            $modal = $soldout ? '#waitlist-modal' : '#ticket-modal';
            echo '<a class="btn ' . $btnCls . ' tour-buy" data-modal-open="' . $modal . '" data-tour="' . e($full) . '" data-tour-id="' . (int)$t['id'] . '">' . $btnText . '</a>';
        }
        echo "</div>\n";
    }
}
