// * Date Helpers

"use strict"

const indoMonths = [
    "Januari",
    "Februari",
    "Maret",
    "April",
    "Mei",
    "Juni",
    "Juli",
    "Agustus",
    "September",
    "Oktober",
    "November",
    "Desember"
]

/**
 * 
 * @param dateTime
 * * example value : 2022-04-28 10:51:44
 * 
 * @returns
 * * can return value : 28 Februari 
 */

const convertDateToIndo = (dateTime) => {

    if(dateTime === undefined || dateTime === null || dateTime === null){
        return null
    }

    let splitDateTime = dateTime.split(' ')
    let splitDate = splitDateTime[0].split('-')
    let indoMonth = splitDate[1].split('')
    indoMonth = indoMonths[indoMonth[1]-1]

    let indoDate = splitDate[2].concat(" ", indoMonth, " ", splitDate[0])
    return indoDate
}

