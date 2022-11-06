import React, { useEffect, useRef, useState } from 'react';
import algoliasearch from 'algoliasearch/lite';
import { Configure, Index, InstantSearch, SearchBox, InfiniteHits } from 'react-instantsearch-dom';
import RefinementList from './components/RefinementList';
import { createURL, searchStateToUrl, urlToSearchState } from './helpers/search-url';

const DEBOUNCE_TIME = 400;
const algoliaClient = algoliasearch(
    process.env.ALGOLIA_APPLICATION_ID,
    process.env.ALGOLIA_SEARCH_API_KEY
);

const searchClient = {
    search(requests) {
        const newRequests = requests.map((request) => {
            if (!request.params.query || request.params.query.length === 0) {
                request.params.analytics = false;
            }

            return request;
        });

        return algoliaClient.search(newRequests);
    }
}

const Search = () => {
    const [searchState, setSearchState] = useState(urlToSearchState(window.location));
    const [indexState, setIndexState] = useState('dev_all');
    const debouncedSetStateRef = useRef(null);

    const onSearchStateChange = (updatedSearchState) => {
        clearTimeout(debouncedSetStateRef.current);

        debouncedSetStateRef.current = setTimeout(() => {
            window.history.pushState('', '', searchStateToUrl(updatedSearchState), updatedSearchState);
        }, DEBOUNCE_TIME);

        setSearchState(updatedSearchState);
    };

    const setIndex = (indexName) => {
        setIndexState(indexName);
    }

    useEffect(() => {
        setSearchState(urlToSearchState(window.location));
    }, [window.location]);

    return (
        <InstantSearch
            searchClient={searchClient}
            searchState={searchState}
            onSearchStateChange={onSearchStateChange}
            createURL={createURL}
            indexName={indexState}
        >
            <a onClick={(event) => {
                event.preventDefault();
                setIndex('dev_all');
            }}>All</a>
            |
            <a onClick={(event) => {
                event.preventDefault();
                setIndex('dev_insights');
            }}>Insights</a>

            <Configure hitsPerPage="9" />
            <SearchBox />
            <InfiniteHits />
        </InstantSearch>
    );
}

export default Search;