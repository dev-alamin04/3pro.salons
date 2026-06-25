@extends('backend.layouts.app')

@section('title', 'Dashboard')

@push('style')
    <style>
        .metric-card {
            background: #27282D;
            border: 1px solid #00b4c8;
            border-radius: 0.8rem;
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            transition: border-color 0.2s ease;
        }

        .metric-card:hover {
            border-color: #00b4c8;
        }

        .metric-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
        }

        .metric-value {
            font-size: 1.6rem;
            font-weight: 700;
            color: #F2F3F5;
            line-height: 1;
        }

        .metric-label {
            font-size: 0.82rem;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            margin-bottom: 15px;
        }

        .metric-sub {
            font-size: 0.78rem;
            color: #9c9ca3;
        }

        .dark_card {
            background: #27282D;
            border: 1px solid #59595A;
            border-radius: 0.8rem;
        }

        .dark_card_header {
            padding: 16px 20px;
            border-bottom: 1px solid #59595A;
        }

        .dark_card_body {
            padding: 20px;
        }

        .section-title {
            font-size: 1rem;
            font-weight: 600;
            color: #F2F3F5;
            margin: 0;
        }

        .badge-role {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 0.72rem;
            font-weight: 500;
            text-transform: capitalize;
        }

        .badge-business {
            background: #1e3a5f;
            color: #60a5fa;
        }

        .badge-agent {
            background: #1a3a2a;
            color: #34d399;
        }

        .badge-influencer {
            background: #3b1a3a;
            color: #c084fc;
        }

        .badge-inreviewed {
            background: #3b2f00;
            color: #E2B84B;
        }

        .badge-approved {
            background: #1a3a2a;
            color: #34d399;
        }

        .badge-rejected {
            background: #3a1a1a;
            color: #E04747;
        }

        .revenue-divider {
            border-color: #59595A;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 0.78rem;
            white-space: nowrap;
        }

        .legend-line {
            display: inline-block;
            width: 20px;
            height: 3px;
            border-radius: 2px;
        }

        /* Fullscreen chart overlay */
        .chart-fullscreen-btn {
            background: transparent;
            border: 1px solid #00b4c8;
            color: #00b4c8;
            border-radius: 6px;
            padding: 4px 10px;
            font-size: 0.78rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .chart-fullscreen-btn:hover {
            border-color: #00b4c8;
            color: #00b4c8;
        }

        .chart-fullscreen-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: #0B0B0D;
            z-index: 99999;
            flex-direction: column;
            padding: 0;
        }

        .chart-fullscreen-overlay.active {
            display: flex;
        }

        .chart-fullscreen-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 24px;
            border-bottom: 1px solid #59595A;
            background: #27282D;
            flex-shrink: 0;
        }

        .chart-fullscreen-title {
            font-size: 1rem;
            font-weight: 600;
            color: #F2F3F5;
            margin: 0;
        }

        .chart-fullscreen-close {
            background: transparent;
            border: 1px solid #59595A;
            color: #9ca3af;
            border-radius: 6px;
            padding: 6px 14px;
            cursor: pointer;
            font-size: 0.82rem;
            transition: all 0.2s;
        }

        .chart-fullscreen-close:hover {
            border-color: #E04747;
            color: #E04747;
        }

        .chart-fullscreen-body {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px;
            overflow: hidden;
        }

        .chart-fullscreen-body canvas,
        .chart-fullscreen-body>div {
            width: 100% !important;
            height: 100% !important;
            max-height: calc(100vh - 100px);
        }

        /* Mobile optimization */
        @media (max-width: 576px) {
            .legend-item {
                font-size: 0.7rem;
            }

            .legend-line {
                width: 14px;
            }
        }
    </style>
@endpush

@section('content')
    <x-breadcrumbs title="Welcome back, {{ Auth::user()?->name }}!" subtitle="Overview of platform activity and key metrics.">
    </x-breadcrumbs>

    @include('backend.layouts.dashboard.partials._metric_card')
@endsection
