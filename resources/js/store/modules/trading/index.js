/**
 * Accounts Module
 */
import webServices from 'WebServices';
import axios from "axios";
import Nprogress from 'nprogress';
import { toQuery } from 'Helpers/helpers';

const state = {
    accounts_data: [],
    accounts_perPage: 10,
    accounts_total: 0,
    accounts_page: 1,
    accounts_loading: false,

    provideSignal_data: [],
    provideSignal_perPage: 10,
    provideSignal_total: 0,
    provideSignal_page: 1,
    provideSignal_loading: false,

    signalDetail_data: [],
    signalDetail_perPage: 10,
    signalDetail_total: 0,
    signalDetail_page: 1,
    signalDetail_information: {},
    signalDetail_loading: false,

    copyDetail_data: [],
    copyDetail_perPage: 10,
    copyDetail_total: 0,
    copyDetail_page: 1,
    copyDetail_information: {},
    copyDetail_loading: false,

    copyingSignal_data: [],
    copyingSignal_perPage: 10,
    copyingSignal_total: 0,
    copyingSignal_page: 1,
    copyingSignal_loading: false,

    availableSignal_data: [],
    selected_strategy: {},
    analyze_data: {},
    availableSignal_perPage: 10,
    availableSignal_total: 0,
    availableSignal_page: 1,
    availableSignal_loading: false,

    useravailableSignal_data: [],
    useranalyze_data: {},
    useravailableSignal_perPage: 10,
    useravailableSignal_total: 0,
    useravailableSignal_page: 1,
    useravailableSignal_loading: false,
}

const getters = {
    accounts_data: state => state.accounts_data,
    accounts_perPage: state => state.accounts_perPage,
    accounts_total: state => state.accounts_total,
    accounts_page: state => state.accounts_page,
    accounts_loading: state => state.accounts_loading,

    provideSignal_data: state => state.provideSignal_data,
    provideSignal_perPage: state => state.provideSignal_perPage,
    provideSignal_total: state => state.provideSignal_total,
    provideSignal_page: state => state.provideSignal_page,
    provideSignal_loading: state => state.provideSignal_loading,

    signalDetail_data: state => state.signalDetail_data,
    signalDetail_perPage: state => state.signalDetail_perPage,
    signalDetail_total: state => state.signalDetail_total,
    signalDetail_page: state => state.signalDetail_page,
    signalDetail_loading: state => state.signalDetail_loading,
    signalDetail_information: state => state.signalDetail_information,

    copyDetail_data: state => state.copyDetail_data,
    copyDetail_perPage: state => state.copyDetail_perPage,
    copyDetail_total: state => state.copyDetail_total,
    copyDetail_page: state => state.copyDetail_page,
    copyDetail_loading: state => state.copyDetail_loading,
    copyDetail_information: state => state.copyDetail_information,

    copyingSignal_data: state => state.copyingSignal_data,
    copyingSignal_perPage: state => state.copyingSignal_perPage,
    copyingSignal_total: state => state.copyingSignal_total,
    copyingSignal_page: state => state.copyingSignal_page,
    copyingSignal_loading: state => state.copyingSignal_loading,

    availableSignal_data: state => state.availableSignal_data,
    analyze_data: state => state.analyze_data,
    selected_strategy: state => state.selected_strategy,
    availableSignal_perPage: state => state.availableSignal_perPage,
    availableSignal_total: state => state.availableSignal_total,
    availableSignal_page: state => state.availableSignal_page,
    availableSignal_loading: state => state.availableSignal_loading,

    useravailableSignal_data: state => state.useravailableSignal_data,
    useranalyze_data: state => state.useranalyze_data,
    useravailableSignal_perPage: state => state.useravailableSignal_perPage,
    useravailableSignal_total: state => state.useravailableSignal_total,
    useravailableSignal_page: state => state.useravailableSignal_page,
    useravailableSignal_loading: state => state.useravailableSignal_loading,
}

const actions = {
    getAccountsAction(context, option) {
        context.commit('setAccountsLoadingHandler', true);
        Nprogress.start();
        const { page, perPage, search } = option;
        axios.get(`${webServices.baseURL}/accounts${toQuery({
            page, perPage, search
        })}`, { headers: { 'Content-Type': 'application/json' } })
            .then(response => {
                const { api_status, page, perPage, total, accounts } = response.data.response;
                if (api_status) {
                    context.commit('setAccountsHandler', { page, perPage, total, accounts });
                } else {
                    context.commit('setAccountsHandler', { page: 1, perPage: 10, total: 0, accounts: [] });
                }
            })
            .catch(error => {
                context.commit('setAccountsHandler', { page: 1, perPage: 10, total: 0, accounts: [] });
                console.log(error);
                console.log("Failed");
            })
            .finally(() => {
                Nprogress.done();
            })
    },

    getProvideSignalAction(context, option) {
        context.commit('setProvideSignalLoadingHandler', false);
        Nprogress.start();
        const { page, perPage, search } = option;
        axios.get(`${webServices.baseURL}/providesources${toQuery({
            page, perPage, search
        })}`, { headers: { 'Content-Type': 'application/json' } })
            .then(response => {
                const { api_status, page, perPage, total, provideSignal } = response.data.response;
                if (api_status) {
                    context.commit('setProvideSignalHandler', { page, perPage, total, provideSignal });
                    console.log(response, '==========');
                } else {
                    context.commit('setProvideSignalHandler', { page: 1, perPage: 10, total: 0, provideSignal: [] });
                }
            })
            .catch(error => {
                context.commit('setProvideSignalHandler', { page: 1, perPage: 10, total: 0, provideSignal: [] });
                console.log(error);
                console.log("Failed");
            })
            .finally(() => {
                Nprogress.done();
            })
    },

    signalDetailAction(context, option) {
        context.commit('setSignalDetailLoadingHandler', false);
        Nprogress.start();
        axios.post(`${webServices.baseURL}/signaldetail`, option, { headers: { 'Content-Type': 'application/json' } })
            .then(response => {
                const { api_status, page, perPage, total, signalDetail, information } = response.data.response;
                if (api_status) {
                    context.commit('setSignalDetailHandler', { page, perPage, total, signalDetail, information });
                } else {
                    context.commit('setSignalDetailHandler', { page: 1, perPage: 10, total: 0, signalDetail: [], information: {} });
                }
            })
            .catch(error => {
                context.commit('setSignalDetailHandler', { page: 1, perPage: 10, total: 0, signalDetail: [], information: {} });
                console.log(error);
                console.log("Failed");
            })
            .finally(() => {
                Nprogress.done();
            })
    },

    copyDetailAction(context, option) {
        context.commit('setCopyDetailLoadingHandler', false);
        Nprogress.start();
        axios.post(`${webServices.baseURL}/copydetail`, option, { headers: { 'Content-Type': 'application/json' } })
            .then(response => {
                const { api_status, page, perPage, total, copyDetail, information } = response.data.response;
                if (api_status) {
                    context.commit('setCopyDetailHandler', { page, perPage, total, copyDetail, information });
                } else {
                    context.commit('setCopyDetailHandler', { page: 1, perPage: 10, total: 0, copyDetail: [], information: {} });
                }
            })
            .catch(error => {
                context.commit('setCopyDetailHandler', { page: 1, perPage: 10, total: 0, copyDetail: [], information: {} });
                console.log(error);
                console.log("Failed");
            })
            .finally(() => {
                Nprogress.done();
            })
    },

    getCopyingSignalAction(context, option) {
        context.commit('setCopyingSignalLoadingHandler', false);
        Nprogress.start();
        const { page, perPage, search, hash } = option;
        let url = `${webServices.baseURL}/copysources${toQuery({ page, perPage, search, account: hash })}`;
        axios.get(url, { headers: { 'Content-Type': 'application/json' } })
            .then(response => {
                const { api_status, page, perPage, total, copyingSignal } = response.data.response;
                if (api_status) {
                    context.commit('setCopyingSignalHandler', { page, perPage, total, copyingSignal });
                } else {
                    context.commit('setCopyingSignalHandler', { page: 1, perPage: 10, total: 0, copyingSignal: [] });
                }
            })
            .catch(error => {
                context.commit('setCopyingSignalHandler', { page: 1, perPage: 10, total: 0, copyingSignal: [] });
                console.log(error);
                console.log("Failed");
            })
            .finally(() => {
                Nprogress.done();
            })
    },

    getAvailableSignalAction(context, option) {
        context.commit('setAvailableSignalLoadingHandler', false);
        Nprogress.start();
        const { page, perPage, search, sortBy, dir } = option;
        axios.get(`${webServices.baseURL}/availablesources${toQuery({
            page, perPage, search, sortBy, dir
        })}`, { headers: { 'Content-Type': 'application/json' } })
            .then(response => {
                const { api_status, page, perPage, total, availableSignal, selected_strategy, analyzeData } = response.data.response;
                if (api_status) {
                    context.commit('setAvailableSignalHandler', { page, perPage, total, availableSignal, analyzeData, selected_strategy });
                } else {
                    context.commit('setAvailableSignalHandler', { page: 1, perPage: 10, total: 0, availableSignal: [], analyzeData: {}, selected_strategy: {} });
                }
            })
            .catch(error => {
                context.commit('setAvailableSignalHandler', { page: 1, perPage: 10, total: 0, availableSignal: [], analyzeData: {}, selected_strategy: {} });
                console.log(error);
                console.log("Failed");
            })
            .finally(() => {
                Nprogress.done();
            })
    },

    getUserAvailableSignalAction(context, option) {
        context.commit('setUserAvailableSignalLoadingHandler', false);
        Nprogress.start();
        const { page, perPage, user_id } = option;
        axios.get(`${webServices.baseURL}/useravailablesources${toQuery({
            page, perPage, user_id
        })}`, { headers: { 'Content-Type': 'application/json' } })
            .then(response => {
                const { api_status, page, perPage, total, availableSignal, analyzeData } = response.data.response;
                console.log(response.data, '---')
                if (api_status) {
                    context.commit('setUserAvailableSignalHandler', { page, perPage, total, availableSignal, analyzeData });
                } else {
                    context.commit('setUserAvailableSignalHandler', { page: 1, perPage: 10, total: 0, availableSignal: [], analyzeData: {} });
                }
            })
            .catch(error => {
                context.commit('setUserAvailableSignalHandler', { page: 1, perPage: 10, total: 0, availableSignal: [], analyzeData: {} });
                console.log(error);
                console.log("Failed");
            })
            .finally(() => {
                Nprogress.done();
            })
    }
}

const mutations = {
    setAccountsHandler(state, payload) {
        const { page, perPage, total, accounts } = payload;
        state.accounts_page = page;
        state.accounts_perPage = perPage;
        state.accounts_total = total;
        state.accounts_data = accounts;
        state.accounts_loading = false;
    },
    setAccountsLoadingHandler(state, loading) {
        state.accounts_loading = loading;
    },

    setProvideSignalHandler(state, payload) {
        const { page, perPage, total, provideSignal } = payload;
        state.provideSignal_page = page;
        state.provideSignal_perPage = perPage;
        state.provideSignal_total = total;
        state.provideSignal_data = provideSignal;
        state.provideSignal_loading = false;
    },
    setProvideSignalLoadingHandler(state, loading) {
        state.provideSignal_loading = loading;
    },

    setCopyingSignalHandler(state, payload) {
        const { page, perPage, total, copyingSignal } = payload;
        state.copyingSignal_page = page;
        state.copyingSignal_perPage = perPage;
        state.copyingSignal_total = total;
        state.copyingSignal_data = copyingSignal;
        state.copyingSignal_loading = false;
    },
    setCopyingSignalLoadingHandler(state, loading) {
        state.copyingSignal_loading = loading;
    },

    setAvailableSignalHandler(state, payload) {
        const { page, perPage, total, availableSignal, selected_strategy, analyzeData } = payload;
        state.availableSignal_page = page;
        state.availableSignal_perPage = perPage;
        state.availableSignal_total = total;
        state.availableSignal_data = availableSignal;
        state.selected_strategy = selected_strategy;
        state.analyze_data = analyzeData;
        state.availableSignal_loading = false;
    },
    setAvailableSignalLoadingHandler(state, loading) {
        state.availableSignal_loading = loading;
    },

    setUserAvailableSignalHandler(state, payload) {
        const { page, perPage, total, availableSignal, analyzeData } = payload;
        state.useravailableSignal_page = page;
        state.useravailableSignal_perPage = perPage;
        state.useravailableSignal_total = total;
        state.useravailableSignal_data = availableSignal;
        state.useranalyze_data = analyzeData;
        state.useravailableSignal_loading = false;
    },
    setUserAvailableSignalLoadingHandler(state, loading) {
        state.useravailableSignal_loading = loading;
    },

    setSignalDetailHandler(state, payload) {
        const { page, perPage, total, signalDetail, information } = payload;
        state.signalDetail_page = page;
        state.signalDetail_perPage = perPage;
        state.signalDetail_total = total;
        state.signalDetail_data = signalDetail;
        state.signalDetail_information = information;
        state.signalDetail_loading = false;
    },
    setSignalDetailLoadingHandler(state, loading) {
        state.signalDetail_loading = loading;
    },

    setCopyDetailHandler(state, payload) {
        const { page, perPage, total, copyDetail, information } = payload;
        state.copyDetail_page = page;
        state.copyDetail_perPage = perPage;
        state.copyDetail_total = total;
        state.copyDetail_data = copyDetail;
        state.copyDetail_information = information;
        state.copyDetail_loading = false;
    },
    setCopyDetailLoadingHandler(state, loading) {
        state.copyDetail_loading = loading;
    }
}

export default {
    state,
    getters,
    actions,
    mutations
}