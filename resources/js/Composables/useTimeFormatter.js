export function useTimeFormatter() {
    function secondsToHHMMSS(seconds) {
        let hours = Math.floor(seconds / 3600);
        let minutes = Math.floor((seconds % 3600) / 60);
        let secs = seconds % 60;

        return [
            hours.toString().padStart(2, '0'),
            minutes.toString().padStart(2, '0'),
            Math.round(secs).toString().padStart(2, '0')
        ].join(':');
    }

    return { secondsToHHMMSS };
}
