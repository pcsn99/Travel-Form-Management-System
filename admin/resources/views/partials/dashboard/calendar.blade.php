<style>

    #travel-calendar {
        aspect-ratio: 4 / 3;
        width: 100%;
    }
    .calendar-controls {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: flex-end;
        gap: 10px;
        margin-bottom: 10px;
    }

    .calendar-controls select {
        padding: 5px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }
  
    @media (max-width: 700px) {
        #travel-calendar {
            height: 360px !important;
        }
    }

</style>

<div class="card">
    <div class="card-body">
        <div class="calendar-controls">
            <select id="calendar-month"></select>
            <select id="calendar-year"></select>
        </div>
        <div id="travel-calendar"></div>
    </div>
</div>
