<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="20 Wings - Lista de trabajos y seguimiento de obra | JJ Construccion">
    <title>20 Wings · Lista de Trabajos | JJ Construccion</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Montserrat:wght@400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        /* ── RESET ─────────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Montserrat', sans-serif;
            background: #0d0d1a;
            color: #fff;
            min-height: 100vh;
            padding-bottom: 100px;
        }

        /* ── HEADER ─────────────────────────────────── */
        .site-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 24px;
            background: linear-gradient(90deg, #1a0000 0%, #0d0d1a 100%);
            border-bottom: 2px solid #e53935;
            position: sticky;
            top: 0;
            z-index: 100;
            gap: 12px;
        }
        .back-link {
            color: rgba(255,255,255,0.55);
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 1px;
            white-space: nowrap;
            transition: color .2s;
        }
        .back-link:hover { color: #fff; }
        .header-center { text-align: center; flex: 1; }
        .brand-logo {
            font-family: 'Bebas Neue', cursive;
            font-size: 30px;
            letter-spacing: 3px;
            color: #e53935;
            line-height: 1;
        }
        .brand-logo span { color: #fff; }
        .brand-sub {
            font-size: 10px;
            letter-spacing: 4px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.35);
            font-weight: 700;
        }
        .pct-ring {
            width: 52px; height: 52px;
            flex-shrink: 0;
        }
        .pct-ring-svg { transform: rotate(-90deg); }
        .pct-ring-track { fill: none; stroke: rgba(255,255,255,0.08); stroke-width: 4; }
        .pct-ring-fill  { fill: none; stroke: #10b981; stroke-width: 4; stroke-linecap: round;
                          stroke-dasharray: 138; stroke-dashoffset: calc(138 - (138 * {{ $stats['pct'] }}) / 100);
                          transition: stroke-dashoffset .8s ease; }
        .pct-ring-text  { fill: #fff; font-size: 11px; font-weight: 700; font-family: 'Montserrat', sans-serif;
                          text-anchor: middle; dominant-baseline: central; }

        /* ── STATS BAR ─────────────────────────────── */
        .stats-bar {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 14px 24px;
            background: #111122;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            flex-wrap: wrap;
        }
        .stat-pill {
            text-align: center;
            min-width: 56px;
        }
        .stat-num {
            font-family: 'Bebas Neue', cursive;
            font-size: 28px;
            line-height: 1;
        }
        .stat-lbl {
            font-size: 9px;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,0.4);
            text-transform: uppercase;
        }
        .stat-completada .stat-num { color: #10b981; }
        .stat-en_progreso .stat-num { color: #3b82f6; }
        .stat-pendiente   .stat-num { color: #64748b; }
        .stat-bar-wrap { flex: 1; min-width: 120px; }
        .stat-bar-track {
            height: 7px;
            background: rgba(255,255,255,0.08);
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 5px;
        }
        .stat-bar-fill {
            height: 100%;
            background: linear-gradient(90deg, #e53935, #10b981);
            border-radius: 4px;
            width: {{ $stats['pct'] }}%;
            transition: width .8s ease;
        }
        .stat-bar-label {
            font-size: 11px;
            font-weight: 700;
            color: rgba(255,255,255,0.6);
        }
        #stat-pct-label { transition: all .3s; }

        /* ── FLASH ─────────────────────────────────── */
        .flash {
            background: rgba(16,185,129,0.12);
            border-left: 3px solid #10b981;
            color: #34d399;
            padding: 12px 24px;
            font-size: 13px;
            font-weight: 700;
        }

        /* ── FILTERS ────────────────────────────────── */
        .filters-wrap {
            padding: 14px 24px;
            background: #111122;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .filter-row {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            align-items: center;
        }
        .filter-row-label {
            font-size: 10px;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,0.3);
            text-transform: uppercase;
            font-weight: 700;
            margin-right: 4px;
        }
        .fbtn {
            padding: 4px 12px;
            border-radius: 20px;
            border: 1px solid rgba(255,255,255,0.14);
            background: transparent;
            color: rgba(255,255,255,0.5);
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            transition: all .2s;
            font-family: 'Montserrat', sans-serif;
            white-space: nowrap;
        }
        .fbtn:hover { border-color: rgba(255,255,255,0.4); color: #fff; }
        .fbtn.active { background: rgba(255,255,255,0.12); border-color: rgba(255,255,255,0.4); color: #fff; }
        .fbtn[data-cat].active { background: var(--cc,rgba(255,255,255,0.12)); border-color: var(--cc-border, rgba(255,255,255,0.4)); color: var(--cc-text,#fff); }

        /* ── GASTOS GENERALES ───────────────────────── */
        .general-receipt-card {
            background: linear-gradient(135deg, #181830 0%, #111122 100%);
            border: 2px solid #10b981;
            border-radius: 20px;
            padding: 20px 24px;
            margin: 22px 24px 0;
            max-width: 1400px;
            box-shadow: 0 16px 40px rgba(16,185,129,0.15);
        }
        @media (min-width: 1440px) {
            .general-receipt-card { margin: 22px auto 0; }
        }
        .general-card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            padding-bottom: 12px;
            margin-bottom: 14px;
            flex-wrap: wrap;
            gap: 10px;
        }
        .general-card-title {
            font-family: 'Bebas Neue', cursive;
            font-size: 26px;
            letter-spacing: 2px;
            color: #10b981;
            display: flex;
            align-items: center;
            gap: 10px;
            line-height: 1;
        }
        .general-card-desc {
            font-size: 13px;
            color: rgba(255,255,255,0.6);
            margin-bottom: 16px;
            line-height: 1.5;
        }

        /* ── TASKS GRID ─────────────────────────────── */
        .tasks-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 18px;
            padding: 22px 24px;
            max-width: 1400px;
            margin: 0 auto;
        }

        /* ── TASK CARD ──────────────────────────────── */
        .task-card {
            background: #131325;
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 16px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: transform .3s, box-shadow .3s, opacity .3s;
            position: relative;
        }
        .task-card:hover { transform: translateY(-3px); box-shadow: 0 14px 40px rgba(0,0,0,.45); }
        .task-card.status-pendiente  { border-left: 3px solid #4b5563; }
        .task-card.status-en_progreso{ border-left: 3px solid #3b82f6; }
        .task-card.status-completada { border-left: 3px solid #10b981; }
        .task-card.status-completada .task-name { text-decoration: line-through; color: rgba(255,255,255,.4); }

        /* Card head */
        .card-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            padding: 12px 14px 0;
            gap: 6px;
            flex-wrap: wrap;
        }
        .cat-chip {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .5px;
            padding: 3px 9px;
            border-radius: 20px;
            border: 1px solid;
        }
        .prio-badge {
            font-size: 10px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 10px;
            white-space: nowrap;
        }
        .prio-alta  { background: rgba(239,68,68,.15);  color: #f87171; }
        .prio-media { background: rgba(245,158,11,.15); color: #fbbf24; }
        .prio-baja  { background: rgba(16,185,129,.15); color: #34d399; }

        /* Task name */
        .task-name {
            font-size: 14px;
            font-weight: 700;
            padding: 10px 14px 4px;
            line-height: 1.35;
            transition: all .3s;
        }
        /* Notes */
        .task-notes {
            font-size: 11px;
            color: rgba(255,255,255,.45);
            padding: 0 14px 8px;
            line-height: 1.5;
            font-style: italic;
        }
        /* Completed date */
        .done-date {
            font-size: 10px;
            color: #34d399;
            padding: 0 14px 6px;
            font-weight: 700;
            letter-spacing: .5px;
        }
        .done-date.hidden { display: none; }

        /* Status buttons */
        .status-btns {
            display: flex;
            gap: 5px;
            padding: 8px 14px;
        }
        .s-btn {
            flex: 1;
            padding: 6px 4px;
            border-radius: 8px;
            border: 1px solid rgba(255,255,255,.1);
            background: transparent;
            color: rgba(255,255,255,.35);
            font-size: 10px;
            font-weight: 700;
            cursor: pointer;
            transition: all .2s;
            font-family: 'Montserrat', sans-serif;
            text-align: center;
        }
        .s-btn:hover { color: #fff; border-color: rgba(255,255,255,.3); }
        .s-btn.s-pend.active   { border-color: #4b5563; background: rgba(75,85,99,.2);   color: #9ca3af; }
        .s-btn.s-prog.active   { border-color: #3b82f6; background: rgba(59,130,246,.2); color: #60a5fa; }
        .s-btn.s-done.active   { border-color: #10b981; background: rgba(16,185,129,.2); color: #34d399; }

        /* PHOTOS & RECEIPTS SECTION */
        .photos-section, .receipts-section {
            border-top: 1px solid rgba(255,255,255,.06);
            padding: 10px 14px;
        }
        .photos-section {
            display: flex;
            gap: 10px;
        }
        .photo-col { flex: 1; display: flex; flex-direction: column; gap: 5px; }
        .photo-col-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .photo-col-label {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,.35);
        }
        /* Edit mode toggle */
        .edit-mode-btn {
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 13px;
            opacity: 0.35;
            transition: opacity .2s;
            padding: 2px 4px;
            line-height: 1;
        }
        .edit-mode-btn:hover { opacity: 0.8; }
        .edit-mode-btn.active { opacity: 1; }
        .thumbs-row, .receipts-list {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            min-height: 8px;
        }
        .p-thumb {
            position: relative;
            width: 64px; height: 64px;
            border-radius: 10px;
            overflow: hidden;
            border: 1px solid rgba(255,255,255,.12);
            cursor: pointer;
            transition: transform .2s;
            flex-shrink: 0;
        }
        .p-thumb:hover { transform: scale(1.04); }
        .p-thumb img { width: 100%; height: 100%; object-fit: cover; display: block; }
        /* Delete button — hidden by default, shown only in edit mode */
        .p-thumb .del-ph {
            position: absolute;
            top: 2px; right: 2px;
            width: 20px; height: 20px;
            background: rgba(220,38,38,.9);
            border: none; border-radius: 50%;
            color: #fff; font-size: 14px; line-height: 20px;
            text-align: center; cursor: pointer;
            display: none; padding: 0;
            font-weight: 700;
            box-shadow: 0 2px 6px rgba(0,0,0,.5);
            transition: transform .15s;
        }
        .p-thumb .del-ph:hover { transform: scale(1.15); }
        /* Show X only when parent thumbs-row has edit-mode class */
        .thumbs-row.edit-mode .p-thumb .del-ph { display: block; }
        .thumbs-row.edit-mode .p-thumb { border-color: rgba(220,38,38,.35); }
        /* Upload buttons row */
        .up-btns-row {
            display: flex;
            gap: 5px;
            margin-top: 4px;
            flex-wrap: wrap;
        }
        .up-btn {
            flex: 1;
            padding: 6px 6px;
            border-radius: 7px;
            border: 1px dashed rgba(255,255,255,.18);
            background: transparent;
            color: rgba(255,255,255,.4);
            font-size: 10px;
            cursor: pointer;
            transition: all .2s;
            font-family: 'Montserrat', sans-serif;
            text-align: center;
            font-weight: 700;
            white-space: nowrap;
            min-width: 0;
        }
        .up-btn.cam-btn:hover        { border-color: #e53935; color: #e53935; background: rgba(229,57,53,.06); }
        .up-btn.gal-btn:hover        { border-color: #f59e0b; color: #f59e0b; background: rgba(245,158,11,.06); }
        .up-btn.cam-btn.despues:hover{ border-color: #10b981; color: #10b981; background: rgba(16,185,129,.06); }
        .up-btn.gal-btn.despues:hover{ border-color: #3b82f6; color: #3b82f6; background: rgba(59,130,246,.06); }

        /* RECEIPTS SPECIFIC */
        .receipts-section {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .receipt-header-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .task-gasto-total {
            font-size: 12px;
            font-weight: 700;
            color: #10b981;
            background: rgba(16,185,129,0.1);
            padding: 2px 8px;
            border-radius: 6px;
        }
        .receipt-thumb {
            width: 64px; height: 64px;
        }
        .rcp-amount {
            position: absolute;
            bottom: 0; left: 0; right: 0;
            background: rgba(0,0,0,0.85);
            color: #10b981;
            font-size: 8px;
            font-weight: 700;
            text-align: center;
            padding: 2px 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .up-btn.receipt-btn {
            border-color: rgba(16,185,129,0.25);
            color: rgba(16,185,129,0.65);
        }
        .up-btn.receipt-btn:hover {
            border-color: #10b981;
            color: #10b981;
        }

        /* ── POST-UPLOAD ACTION SHEET ────────────── */
        .action-sheet-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,.65);
            backdrop-filter: blur(6px);
            z-index: 400;
            align-items: flex-end;
            justify-content: center;
        }
        .action-sheet-overlay.active { display: flex; }
        .action-sheet {
            background: #1a1a30;
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 20px 20px 0 0;
            width: 100%;
            max-width: 500px;
            padding: 20px 20px 36px;
            animation: slideUpSheet .3s ease;
        }
        @keyframes slideUpSheet {
            from { transform: translateY(100%); opacity: 0; }
            to   { transform: translateY(0);    opacity: 1; }
        }
        .action-sheet-title {
            font-family: 'Bebas Neue', cursive;
            font-size: 18px;
            letter-spacing: 2px;
            color: rgba(255,255,255,.5);
            text-align: center;
            margin-bottom: 6px;
        }
        .action-sheet-subtitle {
            font-size: 11px;
            color: rgba(255,255,255,.35);
            text-align: center;
            margin-bottom: 18px;
            font-weight: 600;
        }
        .action-sheet-btns {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .as-btn {
            padding: 14px;
            border-radius: 12px;
            border: 1px solid rgba(255,255,255,.1);
            background: rgba(255,255,255,.05);
            color: #fff;
            font-family: 'Montserrat', sans-serif;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            transition: all .2s;
            text-align: center;
        }
        .as-btn:hover { background: rgba(255,255,255,.1); border-color: rgba(255,255,255,.2); }
        .as-btn.as-cam  { border-color: rgba(229,57,53,.4); color: #f87171; }
        .as-btn.as-cam:hover  { background: rgba(229,57,53,.12); border-color: #e53935; }
        .as-btn.as-gal  { border-color: rgba(245,158,11,.4); color: #fbbf24; }
        .as-btn.as-gal:hover  { background: rgba(245,158,11,.12); border-color: #f59e0b; }
        .as-btn.as-done { border-color: rgba(16,185,129,.4); color: #34d399; }
        .as-btn.as-done:hover { background: rgba(16,185,129,.12); border-color: #10b981; }

        /* Card footer */
        .card-foot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 7px 14px;
            border-top: 1px solid rgba(255,255,255,.04);
        }
        .task-seq { font-size: 10px; color: rgba(255,255,255,.18); font-weight: 700; }
        .del-task-btn {
            background: transparent; border: none;
            cursor: pointer; font-size: 14px;
            opacity: .25; transition: opacity .2s; padding: 2px;
        }
        .del-task-btn:hover { opacity: 1; }

        /* ── FAB ─────────────────────────────────────── */
        .fab {
            position: fixed;
            bottom: 26px; right: 26px;
            background: #e53935;
            color: #fff; border: none;
            border-radius: 50px;
            padding: 14px 20px;
            font-family: 'Montserrat', sans-serif;
            font-size: 13px; font-weight: 700;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(229,57,53,.5);
            display: flex; align-items: center; gap: 8px;
            transition: transform .2s, box-shadow .2s;
            z-index: 90;
        }
        .fab:hover { transform: translateY(-2px) scale(1.04); box-shadow: 0 12px 30px rgba(229,57,53,.6); }
        .fab-icon { font-size: 20px; line-height: 1; }

        /* ── MODAL ───────────────────────────────────── */
        .m-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,.8);
            backdrop-filter: blur(8px);
            z-index: 200;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .m-overlay.active { display: flex; }
        .modal-box {
            background: #1a1a30;
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 20px;
            width: 100%; max-width: 480px;
            max-height: 90vh; overflow-y: auto;
            box-shadow: 0 24px 80px rgba(0,0,0,.8);
            animation: slideUp .3s ease;
        }
        @keyframes slideUp { from { transform: translateY(30px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        .modal-hd {
            display: flex; justify-content: space-between; align-items: center;
            padding: 20px 24px 14px;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .modal-hd h2 { font-family: 'Bebas Neue', cursive; font-size: 22px; letter-spacing: 2px; color: #e53935; }
        .modal-close {
            background: transparent; border: none;
            color: rgba(255,255,255,.4); font-size: 24px;
            cursor: pointer; line-height: 1; transition: color .2s; padding: 0 4px;
        }
        .modal-close:hover { color: #fff; }
        .modal-body { padding: 20px 24px 24px; }
        .fg { margin-bottom: 14px; }
        .fg label { display: block; font-size: 10px; font-weight: 700; letter-spacing: 1px; color: rgba(255,255,255,.4); text-transform: uppercase; margin-bottom: 5px; }
        .fg input, .fg select, .fg textarea {
            width: 100%;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.1);
            border-radius: 10px;
            color: #fff;
            padding: 10px 14px;
            font-family: 'Montserrat', sans-serif;
            font-size: 13px;
            transition: border-color .2s;
            resize: vertical;
        }
        .fg input:focus, .fg select:focus, .fg textarea:focus { outline: none; border-color: #e53935; }
        .fg select option { background: #1a1a30; }
        .fg-row { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .modal-actions { display: flex; gap: 10px; margin-top: 18px; }
        .btn-cancel, .btn-submit {
            flex: 1; padding: 12px; border-radius: 10px;
            font-family: 'Montserrat', sans-serif;
            font-size: 13px; font-weight: 700;
            cursor: pointer; border: none; transition: all .2s;
        }
        .btn-cancel { background: rgba(255,255,255,.07); color: rgba(255,255,255,.5); }
        .btn-cancel:hover { background: rgba(255,255,255,.14); color: #fff; }
        .btn-submit { background: #e53935; color: #fff; box-shadow: 0 4px 16px rgba(229,57,53,.35); }
        .btn-submit:hover { background: #c62828; }

        /* ── LIGHTBOX ────────────────────────────────── */
        .lightbox {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,.96);
            z-index: 500;
            align-items: center;
            justify-content: center;
        }
        .lightbox.active { display: flex; }
        .lb-img { max-width: 92vw; max-height: 92vh; object-fit: contain; border-radius: 8px; box-shadow: 0 24px 80px rgba(0,0,0,.8); display: block; }
        .lb-close {
            position: absolute; top: 14px; right: 14px;
            background: rgba(255,255,255,.1); border: none;
            color: #fff; width: 40px; height: 40px;
            border-radius: 50%; font-size: 22px;
            cursor: pointer; display: flex; align-items: center; justify-content: center;
            transition: background .2s; z-index: 10;
        }
        .lb-close:hover { background: rgba(255,255,255,.2); }
        .lb-caption {
            position: absolute; bottom: 16px; left: 50%; transform: translateX(-50%);
            background: rgba(0,0,0,.6);
            color: rgba(255,255,255,.7);
            font-size: 11px; font-weight: 700; letter-spacing: 2px;
            padding: 5px 14px; border-radius: 20px;
        }

        /* ── NO TASKS ────────────────────────────────── */
        .no-tasks { text-align: center; padding: 60px; color: rgba(255,255,255,.25); font-size: 16px; }

        /* ── UPLOAD SPINNER ──────────────────────────── */
        .up-btn.loading { opacity: .6; cursor: wait; }

        /* ── RESPONSIVE ──────────────────────────────── */
        @media (max-width: 768px) {
            .tasks-grid { grid-template-columns: 1fr; padding: 14px; }
            .stats-bar { gap: 10px; padding: 12px 16px; }
            .filters-wrap { padding: 10px 16px; }
            .site-header { padding: 10px 16px; }
            .fab { bottom: 18px; right: 18px; padding: 12px 16px; }
            .fg-row { grid-template-columns: 1fr; }
            .brand-logo { font-size: 22px; }
        }
    </style>
</head>
<body>

@php
$cats = [
    'mantenimiento' => ['icon' => '🔧', 'label' => 'Mantenimiento', 'color' => '#f59e0b'],
    'pintura'       => ['icon' => '🎨', 'label' => 'Pintura',       'color' => '#8b5cf6'],
    'limpieza'      => ['icon' => '🧹', 'label' => 'Limpieza',      'color' => '#06b6d4'],
    'electrico'     => ['icon' => '⚡', 'label' => 'Eléctrico',     'color' => '#eab308'],
    'fabricacion'   => ['icon' => '🔨', 'label' => 'Fabricación',   'color' => '#f97316'],
    'instalacion'   => ['icon' => '🔩', 'label' => 'Instalación',   'color' => '#3b82f6'],
    'reparacion'    => ['icon' => '🛠️',  'label' => 'Reparación',    'color' => '#ef4444'],
];
@endphp

{{-- ===== HEADER ===== --}}
<header class="site-header">
    <a href="{{ route('jj.construccion') }}" class="back-link">← JJ Construccion</a>
    <div class="header-center">
        <div class="brand-logo">20 <span>Wings</span></div>
        <div class="brand-sub">Lista de Trabajos</div>
    </div>
    {{-- Progress ring --}}
    <div class="pct-ring" title="{{ $stats['pct'] }}% completado">
        <svg class="pct-ring-svg" viewBox="0 0 48 48" width="52" height="52">
            <circle class="pct-ring-track" cx="24" cy="24" r="22"/>
            <circle class="pct-ring-fill"  cx="24" cy="24" r="22"/>
            <text class="pct-ring-text" x="24" y="24">{{ $stats['pct'] }}%</text>
        </svg>
    </div>
</header>

{{-- ===== STATS BAR ===== --}}
<div class="stats-bar">
    <div class="stat-pill">
        <div class="stat-num" id="stat-total">{{ $stats['total'] }}</div>
        <div class="stat-lbl">Total</div>
    </div>
    <div class="stat-bar-wrap">
        <div class="stat-bar-track"><div class="stat-bar-fill" id="stat-bar-fill"></div></div>
        <div class="stat-bar-label" id="stat-pct-label">{{ $stats['pct'] }}% Completado</div>
    </div>
    <div class="stat-pill stat-completada">
        <div class="stat-num" id="stat-completadas">{{ $stats['completadas'] }}</div>
        <div class="stat-lbl">✓ Listas</div>
    </div>
    <div class="stat-pill stat-en_progreso">
        <div class="stat-num" id="stat-prog">{{ $stats['en_progreso'] }}</div>
        <div class="stat-lbl">⟳ En Progreso</div>
    </div>
    <div class="stat-pill stat-pendiente">
        <div class="stat-num" id="stat-pend">{{ $stats['pendientes'] }}</div>
        <div class="stat-lbl">○ Pendientes</div>
    </div>
    <div class="stat-pill stat-gastos" style="min-width: 100px; border-left: 1px solid rgba(255,255,255,0.08); padding-left: 12px;">
        <div class="stat-num" id="stat-gastos" style="color: #10b981;">${{ number_format($stats['total_gastos'], 2) }}</div>
        <div class="stat-lbl">💵 Gastos</div>
    </div>
</div>

{{-- ===== FLASH ===== --}}
@if(session('success'))
<div class="flash">{{ session('success') }}</div>
@endif

{{-- ===== STANDALONE GASTOS GENERALES CARD ===== --}}
@if($generalTask)
<div class="general-receipt-card">
    <div class="general-card-header">
        <div class="general-card-title">
            <span>🧾</span> {{ $generalTask->name }}
        </div>
        <span class="task-gasto-total" id="task-gasto-0" style="font-size: 15px; padding: 4px 12px;">
            Total: ${{ number_format($generalTask->receipts_total, 2) }}
        </span>
    </div>
    <p class="general-card-desc">{{ $generalTask->notes }}</p>
    
    <div class="receipts-list" id="receipts-0" style="margin-bottom: 16px;">
        @foreach($generalTask->receipts as $receipt)
        <div class="p-thumb receipt-thumb" data-rcptid="{{ $receipt->id }}" style="width: 68px; height: 68px;">
            <img src="{{ asset('images/jj_receipts/'.$receipt->filename) }}"
                 alt="Recibo General"
                 class="lb-trigger"
                 data-src="{{ asset('images/jj_receipts/'.$receipt->filename) }}"
                 data-cap="Recibo General: ${{ number_format($receipt->amount, 2) }}">
            <span class="rcp-amount" style="font-size: 9px; padding: 3px 0;">${{ number_format($receipt->amount, 0) }}</span>
            <button class="del-ph" onclick="delReceipt({{ $receipt->id }},this)">×</button>
        </div>
        @endforeach
    </div>
    
    <button class="up-btn receipt-btn" style="padding: 8px 16px; font-size: 11px;" 
            onclick="openReceiptModal(0, '{{ addslashes($generalTask->name) }}')">
        + Subir Factura / Recibo General
    </button>
</div>
@endif

{{-- ===== FILTERS ===== --}}
<div class="filters-wrap">
    {{-- Status --}}
    <div class="filter-row">
        <span class="filter-row-label">Estado:</span>
        <button class="fbtn active" data-ftype="status" data-fval="all"         onclick="doFilter('status','all',this)">Todos</button>
        <button class="fbtn"        data-ftype="status" data-fval="pendiente"    onclick="doFilter('status','pendiente',this)">● Pendiente</button>
        <button class="fbtn"        data-ftype="status" data-fval="en_progreso"  onclick="doFilter('status','en_progreso',this)">⟳ En Progreso</button>
        <button class="fbtn"        data-ftype="status" data-fval="completada"   onclick="doFilter('status','completada',this)">✓ Completada</button>
    </div>
    {{-- Category --}}
    <div class="filter-row">
        <span class="filter-row-label">Cat:</span>
        @foreach($cats as $key => $cfg)
        <button class="fbtn" data-ftype="category" data-fval="{{ $key }}" data-cat="{{ $key }}"
                style="--cc: {{ $cfg['color'] }}20; --cc-border: {{ $cfg['color'] }}80; --cc-text: {{ $cfg['color'] }};"
                onclick="doFilter('category','{{ $key }}',this)">
            {{ $cfg['icon'] }} {{ $cfg['label'] }}
        </button>
        @endforeach
    </div>
    {{-- Priority --}}
    <div class="filter-row">
        <span class="filter-row-label">Prioridad:</span>
        <button class="fbtn" data-ftype="priority" data-fval="alta"  onclick="doFilter('priority','alta',this)">🔴 Alta</button>
        <button class="fbtn" data-ftype="priority" data-fval="media" onclick="doFilter('priority','media',this)">🟡 Media</button>
        <button class="fbtn" data-ftype="priority" data-fval="baja"  onclick="doFilter('priority','baja',this)">🟢 Baja</button>
    </div>
</div>

{{-- ===== TASK GRID ===== --}}
<div class="tasks-grid" id="tasksGrid">
    @forelse($tasks as $i => $task)
    @php $cat = $cats[$task->category] ?? $cats['instalacion']; @endphp
    <div class="task-card status-{{ $task->status }}"
         data-id="{{ $task->id }}"
         data-status="{{ $task->status }}"
         data-category="{{ $task->category }}"
         data-priority="{{ $task->priority }}"
         id="card-{{ $task->id }}">

        {{-- Head --}}
        <div class="card-head">
            <span class="cat-chip"
                  style="background:{{ $cat['color'] }}18; color:{{ $cat['color'] }}; border-color:{{ $cat['color'] }}44;">
                {{ $cat['icon'] }} {{ $cat['label'] }}
            </span>
            <span class="prio-badge prio-{{ $task->priority }}">
                @if($task->priority==='alta') 🔴 Alta
                @elseif($task->priority==='media') 🟡 Media
                @else 🟢 Baja
                @endif
            </span>
        </div>

        {{-- Name --}}
        <h3 class="task-name">{{ $task->name }}</h3>

        {{-- Notes --}}
        @if($task->notes)
        <p class="task-notes">{{ $task->notes }}</p>
        @endif

        {{-- Completed date --}}
        <div class="done-date {{ $task->status !== 'completada' ? 'hidden' : '' }}" id="done-{{ $task->id }}">
            @if($task->completed_at)
            ✓ Completada: {{ $task->completed_at->format('d/m/Y H:i') }}
            @endif
        </div>

        {{-- Status buttons --}}
        <div class="status-btns">
            <button class="s-btn s-pend {{ $task->status==='pendiente'   ? 'active' : '' }}"
                    onclick="setStatus({{ $task->id }},'pendiente')">Pendiente</button>
            <button class="s-btn s-prog {{ $task->status==='en_progreso' ? 'active' : '' }}"
                    onclick="setStatus({{ $task->id }},'en_progreso')">En Progreso</button>
            <button class="s-btn s-done {{ $task->status==='completada'  ? 'active' : '' }}"
                    onclick="setStatus({{ $task->id }},'completada')">✓ Listo</button>
        </div>

        {{-- Photos --}}
        <div class="photos-section">
            {{-- ANTES --}}
            <div class="photo-col">
                <div class="photo-col-header">
                    <span class="photo-col-label">📷 Antes</span>
                    <button class="edit-mode-btn" title="Activar modo eliminar"
                            onclick="toggleEditMode('thumbs-{{ $task->id }}-antes', this)" type="button">🗑️</button>
                </div>
                <div class="thumbs-row" id="thumbs-{{ $task->id }}-antes">
                    @foreach($task->photos->where('type','antes') as $photo)
                    <div class="p-thumb" data-phid="{{ $photo->id }}">
                        <img src="{{ asset('images/jj_tasks/'.$photo->filename) }}"
                             alt="Antes"
                             class="lb-trigger"
                             data-src="{{ asset('images/jj_tasks/'.$photo->filename) }}"
                             data-cap="Antes — {{ $task->name }}">
                        <button class="del-ph" onclick="delPhoto({{ $photo->id }},this)" type="button">×</button>
                    </div>
                    @endforeach
                </div>
                <div class="up-btns-row">
                    <button class="up-btn cam-btn" type="button"
                            onclick="document.getElementById('fi-{{ $task->id }}-antes-cam').click()">📷 Cámara</button>
                    <button class="up-btn gal-btn" type="button"
                            onclick="document.getElementById('fi-{{ $task->id }}-antes-gal').click()">🖼️ Galería</button>
                </div>
                {{-- Camera (capture) --}}
                <input type="file" id="fi-{{ $task->id }}-antes-cam" accept="image/*" capture="environment" style="display:none"
                       onchange="doUpload({{ $task->id }},'antes',this)">
                {{-- Gallery (multiple) --}}
                <input type="file" id="fi-{{ $task->id }}-antes-gal" accept="image/*" multiple style="display:none"
                       onchange="doUpload({{ $task->id }},'antes',this)">
            </div>
            {{-- DESPUÉS --}}
            <div class="photo-col">
                <div class="photo-col-header">
                    <span class="photo-col-label">🏆 Después</span>
                    <button class="edit-mode-btn" title="Activar modo eliminar"
                            onclick="toggleEditMode('thumbs-{{ $task->id }}-despues', this)" type="button">🗑️</button>
                </div>
                <div class="thumbs-row" id="thumbs-{{ $task->id }}-despues">
                    @foreach($task->photos->where('type','despues') as $photo)
                    <div class="p-thumb" data-phid="{{ $photo->id }}">
                        <img src="{{ asset('images/jj_tasks/'.$photo->filename) }}"
                             alt="Después"
                             class="lb-trigger"
                             data-src="{{ asset('images/jj_tasks/'.$photo->filename) }}"
                             data-cap="Después — {{ $task->name }}">
                        <button class="del-ph" onclick="delPhoto({{ $photo->id }},this)" type="button">×</button>
                    </div>
                    @endforeach
                </div>
                <div class="up-btns-row">
                    <button class="up-btn cam-btn despues" type="button"
                            onclick="document.getElementById('fi-{{ $task->id }}-despues-cam').click()">📷 Cámara</button>
                    <button class="up-btn gal-btn despues" type="button"
                            onclick="document.getElementById('fi-{{ $task->id }}-despues-gal').click()">🖼️ Galería</button>
                </div>
                {{-- Camera (capture) --}}
                <input type="file" id="fi-{{ $task->id }}-despues-cam" accept="image/*" capture="environment" style="display:none"
                       onchange="doUpload({{ $task->id }},'despues',this)">
                {{-- Gallery (multiple) --}}
                <input type="file" id="fi-{{ $task->id }}-despues-gal" accept="image/*" multiple style="display:none"
                       onchange="doUpload({{ $task->id }},'despues',this)">
            </div>
        </div>

        {{-- Receipts / Expenses Section --}}
        <div class="receipts-section">
            <div class="receipt-header-row">
                <span class="photo-col-label">🧾 Recibos / Gastos</span>
                <span class="task-gasto-total" id="task-gasto-{{ $task->id }}">${{ number_format($task->receipts_total, 2) }}</span>
            </div>
            <div class="receipts-list" id="receipts-{{ $task->id }}">
                @foreach($task->receipts as $receipt)
                <div class="p-thumb receipt-thumb" data-rcptid="{{ $receipt->id }}">
                    <img src="{{ asset('images/jj_receipts/'.$receipt->filename) }}"
                         alt="Recibo"
                         class="lb-trigger"
                         data-src="{{ asset('images/jj_receipts/'.$receipt->filename) }}"
                         data-cap="Recibo: ${{ number_format($receipt->amount, 2) }} — {{ $task->name }}">
                    <span class="rcp-amount">${{ number_format($receipt->amount, 0) }}</span>
                    <button class="del-ph" onclick="delReceipt({{ $receipt->id }},this)">×</button>
                </div>
                @endforeach
            </div>
            <button class="up-btn receipt-btn" onclick="openReceiptModal({{ $task->id }}, '{{ addslashes($task->name) }}')">
                + Subir Recibo
            </button>
        </div>

        {{-- Footer --}}
        <div class="card-foot">
            <span class="task-seq">#{{ $loop->iteration }}</span>
            <button class="del-task-btn" title="Eliminar tarea" onclick="delTask({{ $task->id }})">🗑️</button>
        </div>
    </div>
    @empty
    <div class="no-tasks" style="grid-column: 1/-1;">Sin tareas. Agrega la primera con el botón +</div>
    @endforelse
</div>

{{-- ===== FAB ===== --}}
<button class="fab" id="fabBtn" onclick="openModal()">
    <span class="fab-icon">+</span>
    <span>Nueva Tarea</span>
</button>

{{-- ===== NEW TASK MODAL ===== --}}
<div class="m-overlay" id="mOverlay" onclick="overlayClose(event)">
    <div class="modal-box">
        <div class="modal-hd">
            <h2>Nueva Tarea</h2>
            <button class="modal-close" onclick="closeModal()">×</button>
        </div>
        <div class="modal-body">
            <form action="{{ route('jj.20wings.store') }}" method="POST">
                @csrf
                <div class="fg">
                    <label>Nombre *</label>
                    <input type="text" name="name" required placeholder="Ej: Pintura de fachada">
                </div>
                <div class="fg-row">
                    <div class="fg">
                        <label>Categoría *</label>
                        <select name="category" required>
                            <option value="mantenimiento">🔧 Mantenimiento</option>
                            <option value="pintura">🎨 Pintura</option>
                            <option value="limpieza">🧹 Limpieza</option>
                            <option value="electrico">⚡ Eléctrico</option>
                            <option value="fabricacion">🔨 Fabricación</option>
                            <option value="instalacion" selected>🔩 Instalación</option>
                            <option value="reparacion">🛠️ Reparación</option>
                        </select>
                    </div>
                    <div class="fg">
                        <label>Prioridad *</label>
                        <select name="priority" required>
                            <option value="alta">🔴 Alta</option>
                            <option value="media" selected>🟡 Media</option>
                            <option value="baja">🟢 Baja</option>
                        </select>
                    </div>
                </div>
                <div class="fg">
                    <label>Notas (opcional)</label>
                    <textarea name="notes" rows="3" placeholder="Detalles adicionales..."></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeModal()">Cancelar</button>
                    <button type="submit" class="btn-submit">✓ Crear Tarea</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== UPLOAD RECEIPT MODAL ===== --}}
<div class="m-overlay" id="rOverlay" onclick="overlayReceiptClose(event)">
    <div class="modal-box modal-box-receipt" style="max-width: 400px;">
        <div class="modal-hd">
            <h2 style="color: #10b981;">Subir Recibo</h2>
            <button class="modal-close" onclick="closeReceiptModal()">×</button>
        </div>
        <div class="modal-body">
            <h4 id="rTaskName" style="font-size: 13px; color: rgba(255,255,255,0.7); margin-bottom: 16px; font-weight: 500; line-height: 1.4;"></h4>
            <form id="receiptForm" onsubmit="submitReceipt(event)">
                <input type="hidden" id="rTaskId">
                <div class="fg">
                    <label>Valor Factura ($) *</label>
                    <input type="number" id="rAmount" step="0.01" min="0" required placeholder="Ej: 450.00">
                </div>
                <div class="fg">
                    <label>Foto de la Factura *</label>
                    <input type="file" id="rPhoto" accept="image/*" required>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn-cancel" onclick="closeReceiptModal()">Cancelar</button>
                    <button type="submit" class="btn-submit" style="background: #10b981; box-shadow: 0 4px 16px rgba(16,185,129,0.3);">✓ Subir Recibo</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ===== LIGHTBOX ===== --}}
<div class="lightbox" id="lightbox" onclick="lbClick(event)">
    <button class="lb-close" onclick="closeLb()">×</button>
    <img src="" id="lbImg" class="lb-img" alt="Foto ampliada">
    <div class="lb-caption" id="lbCap"></div>
</div>

{{-- ===== ACTION SHEET (post-upload) ===== --}}
<div class="action-sheet-overlay" id="asOverlay" onclick="asOverlayClick(event)">
    <div class="action-sheet">
        <div class="action-sheet-title">✓ Foto Subida</div>
        <div class="action-sheet-subtitle" id="asSubtitle">¿Qué deseas hacer ahora?</div>
        <div class="action-sheet-btns">
            <button class="as-btn as-cam" id="asCamBtn" onclick="asTakeAnother('cam')">📷 Tomar otra foto</button>
            <button class="as-btn as-gal" id="asGalBtn" onclick="asTakeAnother('gal')">🖼️ Subir desde galería</button>
            <button class="as-btn as-done" onclick="closeActionSheet()">✓ Listo</button>
        </div>
    </div>
</div>

{{-- ===== JS ===== --}}
<script>
const csrf = document.querySelector('meta[name="csrf-token"]').content;
const activeFilters = { status: 'all', category: 'all', priority: 'all' };

/* ─── EDIT MODE TOGGLE ───────────────── */
function toggleEditMode(rowId, btn) {
    const row = document.getElementById(rowId);
    if (!row) return;
    const isActive = row.classList.toggle('edit-mode');
    btn.classList.toggle('active', isActive);
    btn.title = isActive ? 'Desactivar modo eliminar' : 'Activar modo eliminar';
}

/* ─── ACTION SHEET ───────────────────── */
let _asTaskId = null;
let _asType   = null;

function showActionSheet(taskId, type, uploadedCount) {
    _asTaskId = taskId;
    _asType   = type;
    const label = type === 'antes' ? 'Antes' : 'Después';
    const subtitle = uploadedCount > 1
        ? `✓ ${uploadedCount} fotos subidas — ${label}`
        : `✓ Foto subida — ${label}`;
    document.getElementById('asSubtitle').textContent = subtitle;
    document.getElementById('asOverlay').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeActionSheet() {
    document.getElementById('asOverlay').classList.remove('active');
    document.body.style.overflow = '';
    _asTaskId = null;
    _asType   = null;
}

function asOverlayClick(e) {
    if (e.target.id === 'asOverlay') closeActionSheet();
}

function asTakeAnother(mode) {
    closeActionSheet();
    if (_asTaskId === null || !_asType) return;
    const inputId = `fi-${_asTaskId}-${_asType}-${mode}`;
    const el = document.getElementById(inputId);
    if (el) { el.value = ''; el.click(); }
}

/* ─── IMAGE COMPRESSION ──────────────────── */
function compressImage(file, maxWidth = 1920, maxHeight = 1920, quality = 0.8) {
    return new Promise((resolve) => {
        if (!file || !file.type.startsWith('image/')) {
            resolve(file);
            return;
        }

        const reader = new FileReader();
        reader.readAsDataURL(file);
        reader.onload = (event) => {
            const img = new Image();
            img.src = event.target.result;
            img.onload = () => {
                let width = img.width;
                let height = img.height;

                if (width > maxWidth || height > maxHeight) {
                    if (width > height) {
                        height = Math.round((height * maxWidth) / width);
                        width = maxWidth;
                    } else {
                        width = Math.round((width * maxHeight) / height);
                        height = maxHeight;
                    }
                }

                const canvas = document.createElement('canvas');
                canvas.width = width;
                canvas.height = height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, width, height);

                canvas.toBlob((blob) => {
                    if (blob) {
                        const name = file.name.substring(0, file.name.lastIndexOf('.')) + '.jpg';
                        const compressedFile = new File([blob], name, {
                            type: 'image/jpeg',
                            lastModified: Date.now()
                        });
                        resolve(compressedFile);
                    } else {
                        resolve(file);
                    }
                }, 'image/jpeg', quality);
            };
            img.onerror = () => resolve(file);
        };
        reader.onerror = () => resolve(file);
    });
}

/* ─── FILTER ─────────────────────────────── */
function doFilter(type, val, btn) {
    if (activeFilters[type] === val && val !== 'all') {
        activeFilters[type] = 'all';
        document.querySelectorAll(`.fbtn[data-ftype="${type}"]`).forEach(b => b.classList.remove('active'));
        document.querySelector(`.fbtn[data-ftype="${type}"][data-fval="all"]`)?.classList.add('active');
    } else {
        activeFilters[type] = val;
        document.querySelectorAll(`.fbtn[data-ftype="${type}"]`).forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }
    applyFilters();
}

function applyFilters() {
    document.querySelectorAll('.task-card').forEach(card => {
        const ok =
            (activeFilters.status   === 'all' || card.dataset.status   === activeFilters.status)  &&
            (activeFilters.category === 'all' || card.dataset.category === activeFilters.category) &&
            (activeFilters.priority === 'all' || card.dataset.priority === activeFilters.priority);
        card.style.display = ok ? '' : 'none';
    });
}

/* ─── STATUS UPDATE ──────────────────────── */
async function setStatus(id, status) {
    try {
        const res  = await fetch(`/jj-construccion/20wings-tareas/task/${id}/status`, {
            method: 'PATCH',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({ status }),
        });
        const data = await res.json();
        if (!data.success) return;

        const card = document.getElementById(`card-${id}`);
        card.dataset.status = status;

        // Border class
        card.classList.remove('status-pendiente','status-en_progreso','status-completada');
        card.classList.add(`status-${status}`);

        // Strike name
        card.querySelector('.task-name').style.textDecoration = status === 'completada' ? 'line-through' : '';
        card.querySelector('.task-name').style.color = status === 'completada' ? 'rgba(255,255,255,.4)' : '';

        // Status buttons
        card.querySelectorAll('.s-btn').forEach(b => b.classList.remove('active'));
        const map = { pendiente: 's-pend', en_progreso: 's-prog', completada: 's-done' };
        card.querySelector(`.s-btn.${map[status]}`)?.classList.add('active');

        // Completed date
        const doneEl = document.getElementById(`done-${id}`);
        if (status === 'completada' && data.task.completed_at) {
            const d = new Date(data.task.completed_at);
            doneEl.textContent = `✓ Completada: ${d.toLocaleDateString('es-ES',{day:'2-digit',month:'short',year:'numeric'})} ${d.toLocaleTimeString('es-ES',{hour:'2-digit',minute:'2-digit'})}`;
            doneEl.classList.remove('hidden');
        } else {
            doneEl.classList.add('hidden');
        }

        applyFilters();
        refreshStats();
    } catch(e) {
        alert('Error al actualizar estado');
    }
}

/* ─── STATS REFRESH ──────────────────────── */
function refreshStats() {
    const cards = [...document.querySelectorAll('.task-card')];
    const total = cards.length;
    const done  = cards.filter(c => c.dataset.status === 'completada').length;
    const prog  = cards.filter(c => c.dataset.status === 'en_progreso').length;
    const pend  = cards.filter(c => c.dataset.status === 'pendiente').length;
    const pct   = total ? Math.round((done / total) * 100) : 0;

    document.getElementById('stat-total').textContent        = total;
    document.getElementById('stat-completadas').textContent  = done;
    document.getElementById('stat-prog').textContent         = prog;
    document.getElementById('stat-pend').textContent         = pend;
    document.getElementById('stat-pct-label').textContent    = pct + '% Completado';
    document.getElementById('stat-bar-fill').style.width     = pct + '%';

    // Ring
    const ring = document.querySelector('.pct-ring-fill');
    if (ring) ring.style.strokeDashoffset = 138 - (138 * pct / 100);
    const ringText = document.querySelector('.pct-ring-text');
    if (ringText) ringText.textContent = pct + '%';
}

/* ─── PHOTO UPLOAD (multi-file) ─────────── */
async function doUpload(taskId, type, input) {
    const files = [...input.files];
    if (!files.length) return;

    const row  = document.getElementById(`thumbs-${taskId}-${type}`);
    let uploaded = 0;

    // Disable all upload buttons for this col
    const card = document.getElementById(`card-${taskId}`);
    const upBtns = card ? [...card.querySelectorAll(`.up-btn[onclick*="fi-${taskId}-${type}"]`)] : [];
    // also grab buttons by convention
    const camBtn = document.getElementById(`fi-${taskId}-${type}-cam`)?.previousElementSibling;
    const galBtn = document.getElementById(`fi-${taskId}-${type}-gal`)?.previousElementSibling;
    const allBtns = card
        ? [...card.querySelectorAll('.up-btns-row .up-btn')]
        : [];

    allBtns.forEach(b => { b.disabled = true; b.classList.add('loading'); });

    for (let i = 0; i < files.length; i++) {
        const progressText = files.length > 1
            ? `Subiendo ${i + 1}/${files.length}...`
            : 'Subiendo...';
        allBtns.forEach(b => { b.textContent = progressText; });

        try {
            const compressedFile = await compressImage(files[i]);

            const fd = new FormData();
            fd.append('photo', compressedFile);
            fd.append('type', type);
            fd.append('_token', csrf);

            const res = await fetch(`/jj-construccion/20wings-tareas/task/${taskId}/photo`, {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: fd
            });

            let data;
            try { data = await res.json(); }
            catch(jsonErr) { throw new Error('Respuesta inválida del servidor.'); }

            if (res.ok && data.success) {
                const div = document.createElement('div');
                div.className = 'p-thumb';
                div.dataset.phid = data.photo.id;
                div.innerHTML = `
                    <img src="${data.photo.url}" alt="${type}" class="lb-trigger"
                         data-src="${data.photo.url}" data-cap="${type === 'antes' ? 'Antes' : 'Después'}">
                    <button class="del-ph" onclick="delPhoto(${data.photo.id},this)" type="button">×</button>
                `;
                if (row) row.appendChild(div);
                uploaded++;
            } else {
                let msg = `Error foto ${i + 1}`;
                if (data?.errors?.photo) msg += ': ' + data.errors.photo.join(', ');
                else if (data?.message)  msg += ': ' + data.message;
                alert(msg);
            }
        } catch(e) {
            console.error(e);
            alert(`Error de conexión (foto ${i + 1}): ` + e.message);
        }
    }

    // Restore buttons
    allBtns.forEach(b => { b.disabled = false; b.classList.remove('loading'); });
    // Restore original labels
    if (card) {
        card.querySelectorAll('.up-btns-row .cam-btn').forEach(b => {
            if (!b.classList.contains('despues')) b.textContent = '📷 Cámara';
            else b.textContent = '📷 Cámara';
        });
        card.querySelectorAll('.up-btns-row .gal-btn').forEach(b => {
            b.textContent = '🖼️ Galería';
        });
    }
    input.value = '';

    // Show action sheet if at least one photo was uploaded
    if (uploaded > 0) {
        showActionSheet(taskId, type, uploaded);
    }
}


/* ─── LIGHTBOX ───────────────────────────── */
document.addEventListener('click', e => {
    if (e.target.classList.contains('lb-trigger')) {
        document.getElementById('lbImg').src = e.target.dataset.src || e.target.src;
        document.getElementById('lbCap').textContent = e.target.dataset.cap || '';
        document.getElementById('lightbox').classList.add('active');
        document.body.style.overflow = 'hidden';
    }
});

function lbClick(e) { if (e.target.id === 'lightbox') closeLb(); }
function closeLb() {
    document.getElementById('lightbox').classList.remove('active');
    document.getElementById('lbImg').src = '';
    document.body.style.overflow = '';
}

/* ─── DELETE PHOTO ───────────────────────── */
async function delPhoto(id, btn) {
    if (!confirm('¿Eliminar foto?')) return;
    const res  = await fetch(`/jj-construccion/20wings-tareas/photo/${id}`, {
        method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrf },
    });
    const data = await res.json();
    if (data.success) btn.closest('.p-thumb').remove();
}

/* ─── DELETE TASK ────────────────────────── */
async function delTask(id) {
    if (!confirm('¿Eliminar esta tarea permanentemente?')) return;
    const res  = await fetch(`/jj-construccion/20wings-tareas/task/${id}`, {
        method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrf },
    });
    const data = await res.json();
    if (data.success) {
        const card = document.getElementById(`card-${id}`);
        card.style.transition = 'transform .3s, opacity .3s';
        card.style.transform  = 'scale(0.8)';
        card.style.opacity    = '0';
        setTimeout(() => { card.remove(); refreshStats(); }, 320);
    }
}

/* ─── MODAL ──────────────────────────────── */
function openModal()  { document.getElementById('mOverlay').classList.add('active');    document.body.style.overflow = 'hidden'; }
function closeModal() { document.getElementById('mOverlay').classList.remove('active'); document.body.style.overflow = ''; }
function overlayClose(e) { if (e.target.id === 'mOverlay') closeModal(); }

/* ─── RECEIPTS MODAL ─────────────────────── */
function openReceiptModal(taskId, taskName) {
    document.getElementById('rTaskId').value = taskId;
    document.getElementById('rTaskName').textContent = "Categoría: " + taskName;
    document.getElementById('rOverlay').classList.add('active');
    document.body.style.overflow = 'hidden';
}
function closeReceiptModal() {
    document.getElementById('rOverlay').classList.remove('active');
    document.getElementById('receiptForm').reset();
    document.body.style.overflow = '';
}
function overlayReceiptClose(e) { if (e.target.id === 'rOverlay') closeReceiptModal(); }

/* ─── SUBMIT RECEIPT ─────────────────────── */
async function submitReceipt(e) {
    e.preventDefault();
    const id = document.getElementById('rTaskId').value;
    const amount = document.getElementById('rAmount').value;
    const file = document.getElementById('rPhoto').files[0];

    if (!file || !amount) return;

    const submitBtn = e.target.querySelector('button[type="submit"]');
    const origText = submitBtn.textContent;
    submitBtn.textContent = 'Subiendo...';
    submitBtn.disabled = true;

    try {
        const compressedFile = await compressImage(file);

        const fd = new FormData();
        fd.append('photo', compressedFile);
        fd.append('amount', amount);
        fd.append('_token', csrf);

        const res = await fetch(`/jj-construccion/20wings-tareas/task/${id}/receipt`, {
            method: 'POST',
            headers: { 'Accept': 'application/json' },
            body: fd
        });
        
        let data;
        try {
            data = await res.json();
        } catch(jsonErr) {
            throw new Error('La respuesta del servidor no es válida.');
        }

        if (res.ok && data.success) {
            // Append new thumbnail to receipts list
            const list = document.getElementById(`receipts-${id}`);
            const div = document.createElement('div');
            div.className = 'p-thumb receipt-thumb';
            div.dataset.rcptid = data.receipt.id;
            if (id == 0) {
                div.style.width = '68px';
                div.style.height = '68px';
            }
            div.innerHTML = `
                <img src="${data.receipt.url}" alt="Recibo" class="lb-trigger"
                     data-src="${data.receipt.url}" data-cap="${id == 0 ? 'Recibo General' : 'Recibo'}: $${Number(data.receipt.amount).toLocaleString('es-ES', {minimumFractionDigits: 2})}">
                <span class="rcp-amount" style="${id == 0 ? 'font-size: 9px; padding: 3px 0;' : ''}">$${Number(data.receipt.amount).toLocaleString('es-ES', {maximumFractionDigits: 0})}</span>
                <button class="del-ph" onclick="delReceipt(${data.receipt.id},this)">×</button>
            `;
            list.appendChild(div);

            // Update task total
            document.getElementById(`task-gasto-${id}`).textContent = (id == 0 ? 'Total: ' : '') + `$${Number(data.task_total).toLocaleString('es-ES', {minimumFractionDigits: 2})}`;

            // Update stats bar overall total
            document.getElementById('stat-gastos').textContent = `$${Number(data.overall_total).toLocaleString('es-ES', {minimumFractionDigits: 2})}`;

            closeReceiptModal();
        } else {
            let errorMsg = 'Error al subir el recibo';
            if (data && data.errors && data.errors.photo) {
                errorMsg += ': ' + data.errors.photo.join(', ');
            } else if (data && data.message) {
                errorMsg += ': ' + data.message;
            }
            alert(errorMsg);
        }
    } catch(e) {
        console.error(e);
        alert('Error de conexión al subir el recibo: ' + e.message);
    } finally {
        submitBtn.textContent = origText;
        submitBtn.disabled = false;
    }
}

/* ─── DELETE RECEIPT ─────────────────────── */
async function delReceipt(id, btn) {
    if (!confirm('¿Eliminar este recibo?')) return;
    try {
        const res = await fetch(`/jj-construccion/20wings-tareas/receipt/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': csrf }
        });
        const data = await res.json();

        if (data.success) {
            const thumb = btn.closest('.p-thumb');
            const list = thumb.closest('.receipts-list');
            const taskId = list.id.replace('receipts-', '');

            thumb.remove();

            // Update task total
            document.getElementById(`task-gasto-${taskId}`).textContent = (taskId == 0 ? 'Total: ' : '') + `$${Number(data.task_total).toLocaleString('es-ES', {minimumFractionDigits: 2})}`;

            // Update stats bar overall total
            document.getElementById('stat-gastos').textContent = `$${Number(data.overall_total).toLocaleString('es-ES', {minimumFractionDigits: 2})}`;
        } else {
            alert('Error al eliminar el recibo');
        }
    } catch(e) {
        alert('Error de conexión al eliminar el recibo');
    }
}

// ESC to close
document.addEventListener('keydown', e => {
    if (e.key === 'Escape') { closeModal(); closeReceiptModal(); closeLb(); }
});
</script>
</body>
</html>
