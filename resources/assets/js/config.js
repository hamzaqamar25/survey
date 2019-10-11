const apiDomain = Laravel.apiDomain;
export const siteName = Laravel.siteName;

export const api = {
	login: apiDomain + '/authenticate',
	currentUser: apiDomain + '/user',
	updateUserProfile: apiDomain + '/user/profile/update',
	updateUserPassword: apiDomain + '/user/password/update',
	getAllPublisher : apiDomain + '/publisher/getAll',
	getPublisher : apiDomain + '/publisher/get',
	updatePublisher : apiDomain + '/publisher/update',
	addPublisher : apiDomain + '/publisher/add',

	getAllClient : apiDomain + '/client/getAll',
	getClient : apiDomain + '/client/get',
	updateClient : apiDomain + '/client/update',
	addClient : apiDomain + '/client/add',
	
	getAllSurvey : apiDomain + '/survey/getAll',
	getSurvey : apiDomain + '/survey/get',
	updateSurvey : apiDomain + '/survey/update',
	addSurvey : apiDomain + '/survey/add',
	getSurveyPublisher : apiDomain + '/survey/getSurveyPublisher',
	getSurveyReport : apiDomain + '/survey/getSurveyReport',
	getSurveyDetailReport : apiDomain + '/survey/getSurveyDetailReport'
};