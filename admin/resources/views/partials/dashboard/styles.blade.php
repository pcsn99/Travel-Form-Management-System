
<style>
    body {
        background-color: #f0f2f5;
        color: #17224D;
        font-family: 'Inter', sans-serif;
        padding: 40px;
    }

    .dashboard-header {
        background-image: url('/images/bg.jpeg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-color: rgba(23, 34, 77, 0.85);
        background-blend-mode: overlay;
        padding: 20px;
        font-size: 26px;
        font-weight: bold;
        text-align: center;
        color: white;
        border-bottom: 3px solid #17224D;
        margin-bottom: 40px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.3);
        border-radius: 8px;
    }

    .container-custom {
        max-width: 100%;
        margin: auto;
    }

    .card {
        border-radius: 12px;
        box-shadow: 0px 5px 10px rgba(0, 0, 0, 0.3);
        background: rgba(255, 255, 255, 0.95);
        padding: 10px;
        margin-bottom: 50px;
    }

    .card-header {
        background-image: url('/images/bg.jpeg');
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        background-color: rgba(23, 34, 77, 0.85);
        background-blend-mode: overlay;
        color: white;
        font-size: 18px;
        font-weight: bold;
        border-radius: 6px 6px 0 0;
        padding: 15px;
        text-align: center;
    }

    .table-responsive-custom {
        width: 100%;
        overflow-x: auto;
        margin-top: 10px;
    }

    .table {
        border-radius: 8px;
        background: rgba(255, 255, 255, 0.95);
    }

    .table thead {
        background-color: #2d3e78 !important;
        color: #ffffff;
    }

    .table th, .table td {
        padding: 12px;
        border: 1px solid #17224D;
    }

    .status-badge {
        display: inline-block;
        padding: 5px 10px;
        font-size: 13px;
        font-weight: bold;
        border-radius: 12px;
        text-transform: capitalize;
    }

    .badge-approved {
        background-color: #198754;
        color: white;
    }

    .badge-pending {
        background-color: #ffc107;
        color: black;
    }

    .badge-declined {
        background-color: #dc3545;
        color: white;
    }

    .badge-default {
        background-color: #6c757d;
        color: white;
    }

    #travel-calendar {
        max-width: 1000px;
        margin: auto;
        border: 1px solid #17224D;
        padding: 15px;
        background-color: rgba(255, 255, 255, 0.95);
        border-radius: 8px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
    }

    .fc-event-title {
        display: none;
    }

    @media (max-width: 768px) {
        body {
            padding: 20px;
        }

        .table th, .table td {
            font-size: 13px;
            padding: 8px;
        }

        .dashboard-header {
            font-size: 22px;
        }

        .card {
            padding: 3px;
        }

        #travel-calendar {
            padding: 10px;
        }
    }
</style>
