list_news = [
    {
        image:"../imagies/news1.webp",
        description:"Прорив у безболісному лікуванні карієсу!",
        date: new Date(2025, 1, 20, 10, 30, 0)
    },

    {
        image:"../imagies/news2.webp",
        description:"Революція в стоматології: робот-стоматолог під управлінням ШІ!",
        date: new Date(2025, 1, 10, 10, 0, 0)
    },

    {
        image:"../imagies/news3.webp",
        description:"Прорив: 3D-друковані зуби тепер міцніші за натуральні!",
        date: new Date(2025, 6, 15, 21, 5, 0)
    },

    {
        image:"../imagies/news4.webp",
        description:"Прорив: 3D-друковані зуби тепер міцніші за натуральні!",
        date: new Date(2024, 10, 28, 20, 15, 0)
    },

]

function sorting_rule(item1, item2)
{
    if (item2[date].getFullYear() > item1[date].getFullYear())
    {
        return item2[date].getFullYear() - item1[date].getFullYear()
    }

    else if (item2[date].getMonth() > item1[date].getMonth())
    {
        return item2[date].getMonth() - item1[date].getMonth()
    }

    else if (item2[date].getDate() > item1[date].getDate())
    {
        return item2[date].getDate() - item1[date].getDate()
    }
}