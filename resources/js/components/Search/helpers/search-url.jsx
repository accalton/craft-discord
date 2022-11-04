import qs from 'qs';

const setQuery = state => {
    return state.query ? state.query : '';
}

const createURL = state => {
    let url = {};
    let query = setQuery(state);
    if (query) {
        url['query'] = query;
    }

    for (const list in state.refinementList) {
        if (state.refinementList[list]) {
            url[list] = state.refinementList[list];
        }
    }

    return `?${qs.stringify(url)}`;
};

const searchStateToUrl = searchState => {
    return searchState ? createURL(searchState) : '';
};

const urlToSearchState = ({ search }) => {
    let state = qs.parse(search.slice(1));

    let refinementList = {};
    if (state.insightTopics) {
        refinementList['insightTopics'] = state.insightTopics;
    }

    if (state.insightCategories) {
        refinementList['insightCategories'] = state.insightCategories;
    }

    return {
        query: state.query,
        refinementList: refinementList
    };
};

export { createURL, searchStateToUrl, urlToSearchState };
