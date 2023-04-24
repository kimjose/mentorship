const DateFormatter = {
    monthNames: [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ],
    dayNames: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
    formatDate: function (date, format) {
        var self = this;
        format = self.getProperDigits(format, /d+/gi, date.getDate());
        format = self.getProperDigits(format, /M+/g, date.getMonth() + 1);
        format = format.replace(/y+/gi, function (y) {
            var len = y.length;
            var year = date.getFullYear();
            if (len == 2)
                return (year + "").slice(-2);
            else if (len == 4)
                return year;
            return y;
        })
        format = self.getProperDigits(format, /H+/g, date.getHours());
        format = self.getProperDigits(format, /h+/g, self.getHours12(date.getHours()));
        format = self.getProperDigits(format, /m+/g, date.getMinutes());
        format = self.getProperDigits(format, /s+/gi, date.getSeconds());
        format = format.replace(/a/ig, function (a) {
            var amPm = self.getAmPm(date.getHours())
            if (a === 'A')
                return amPm.toUpperCase();
            return amPm;
        })
        format = self.getFullOr3Letters(format, /d+/gi, self.dayNames, date.getDay())
        format = self.getFullOr3Letters(format, /M+/g, self.monthNames, date.getMonth())
        return format;
    },
    getProperDigits: function (format, regex, value) {
        return format.replace(regex, function (m) {
            var length = m.length;
            if (length == 1)
                return value;
            else if (length == 2)
                return ('0' + value).slice(-2);
            return m;
        })
    },
    getHours12: function (hours) {
        // https://stackoverflow.com/questions/10556879/changing-the-1-24-hour-to-1-12-hour-for-the-gethours-method
        return (hours + 24) % 12 || 12;
    },
    getAmPm: function (hours) {
        // https://stackoverflow.com/questions/8888491/how-do-you-display-javascript-datetime-in-12-hour-am-pm-format
        return hours >= 12 ? 'pm' : 'am';
    },
    getFullOr3Letters: function (format, regex, nameArray, value) {
        return format.replace(regex, function (s) {
            var len = s.length;
            if (len == 3)
                return nameArray[value].substr(0, 3);
            else if (len == 4)
                return nameArray[value];
            return s;
        })
    }
}

const customConfirm = (title, message, yesCallback, noCallback) => {
    $('#modalConfirm').modal('show');
    $('#modalTitle').html(title)
    $('#modalMessage').html(message)
    $('#btnModalYes').click(function () {
        $('#modalConfirm').modal('hide');
        yesCallback();
    });
    $('#btnModalNo').click(function () {
        $('#modalConfirm').modal('hide');
        noCallback();
    });
}

const showModalDateFilter = (callback, startDateRequired = false, endDateRequired = false) => {
    $('#modalDateFilter').modal('show');
    let btnDateFilterContinue = document.getElementById('btnDateFilterContinue')
    btnDateFilterContinue.addEventListener('click', () => {
        let startDate = document.getElementById('inputModalStartDate').value;
        let endDate = document.getElementById('inputModalEndDate').value;
        if (startDateRequired && startDate === '') {
            toastr.error("Start date is required")
            return;
        }
        if (endDateRequired && endDate === '') {
            toastr.error("End date is required.")
            return;
        }
        if (startDate !== '' && endDate !== '' && (new Date(startDate)).getTime() > (new Date(endDate)).getTime()) {
            toastr.error("End date must be after/(same as) the start date...SMH")
            return
        }
        callback(startDate, endDate)
        $('#modalDateFilter').modal('hide');
    })
}


/***
 * Calculates distance between two point
 * @param pointA Array of [latitude, longitude]
 * @param pointB Array of [latitude, longitude]
 *
 * @returns d distance in Km
 * */
const getDistanceFromCoordinates = (pointA, pointB) => {
    let R = 6378; // Radius of the earth in km
    let dLat = deg2rad(pointB[0]-pointA[0]);  // deg2rad below
    let dLon = deg2rad(pointB[1]-pointA[1]);
    let a =
        Math.sin(dLat/2) * Math.sin(dLat/2) +
        Math.cos(deg2rad(pointA[0])) * Math.cos(deg2rad(pointB[0])) *
        Math.sin(dLon/2) * Math.sin(dLon/2)
    ;
    let c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a));
     // Distance in km
    return R * c;
}

function deg2rad(deg) {
    return deg * (Math.PI/180)
}

