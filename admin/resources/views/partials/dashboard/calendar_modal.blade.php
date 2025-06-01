<div class="modal fade" id="travelModal" tabindex="-1" aria-labelledby="travelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="travelModalLabel">Travel Info</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="travelModalBody">Loading...</div>
        </div>
    </div>
</div>

<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('travel-calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            nowIndicator: true,
            selectable: true,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: ''
            },
            views: {
                dayGridMonth: {
                    titleFormat: { year: 'numeric', month: 'long' }
                }
            },
            dateClick: function (info) {
                const modal = new bootstrap.Modal(document.getElementById('travelModal'));
                document.getElementById('travelModalLabel').textContent = `Travel Status for ${info.dateStr}`;
                const body = document.getElementById('travelModalBody');
                body.innerHTML = 'Loading...';

                fetch(`/admin/travel-calendar/details/${info.dateStr}`)
                    .then(res => res.ok ? res.json() : Promise.reject(res.status))
                    .then(data => {
                        let html = `<h5>Traveling Members (${data.traveling.length})</h5>`;
                        html += data.traveling.length ? '<ul>' + data.traveling.map(n => `<li>${n}</li>`).join('') + '</ul>' : '<p>None</p>';
                        html += `<h5 class="mt-3">Available Members (${data.available.length})</h5>`;
                        html += data.available.length ? '<ul>' + data.available.map(n => `<li>${n}</li>`).join('') + '</ul>' : '<p>None</p>';
                        body.innerHTML = html;
                    })
                    .catch(error => {
                        body.innerHTML = `<p class='text-danger'>Error loading data. (${error})</p>`;
                    });

                modal.show();
            },
            events: @json($calendarEvents)
        });

        calendar.render();

        // Month/Year selectors
        const monthSelect = document.getElementById('calendar-month');
        const yearSelect = document.getElementById('calendar-year');
        const currentYear = new Date().getFullYear();

        for (let m = 0; m < 12; m++) {
            const opt = document.createElement('option');
            opt.value = m;
            opt.text = new Date(0, m).toLocaleString('default', { month: 'long' });
            monthSelect.appendChild(opt);
        }

        for (let y = currentYear - 5; y <= currentYear + 5; y++) {
            const opt = document.createElement('option');
            opt.value = y;
            opt.text = y;
            yearSelect.appendChild(opt);
        }

        monthSelect.value = new Date().getMonth();
        yearSelect.value = currentYear;

        function updateCalendarDate() {
            const newDate = new Date(yearSelect.value, monthSelect.value, 1);
            calendar.gotoDate(newDate);
        }

        monthSelect.addEventListener('change', updateCalendarDate);
        yearSelect.addEventListener('change', updateCalendarDate);
    });
</script>
