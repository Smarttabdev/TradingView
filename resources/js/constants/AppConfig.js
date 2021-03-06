/**
 * App Config File
 */
export default {
	darkLogo: '/static/img/Black on Transparent.png',							    // dark logo
	brand: 'EagleFX',                                        			        // Brand Name
	copyrightText: 'EagleFX © 2021 All Rights Reserved.',                     // Copyright Text
	enableUserTour: process.env.NODE_ENV === 'production' ? true : false,   // Enable User Tour
	weatherApiId: 'b1b15e88fa797225412429c1c50c122a1',						// weather API Id
	weatherApiKey: '69b72ed255ce5efad910bd946685883a'						// weather APi key
}
