export default {
    baseURL: process.env.NODE_ENV === 'development' ? '/api' : '/api',
    // baseURL: process.env.NODE_ENV === 'development' ? 'http://10.10.11.83:8000/api' : 'https://m.zereeshekhbzenee.com/api',
    countryListURL: "https://restcountries.eu/rest/v2/all"
};