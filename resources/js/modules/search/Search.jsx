import React from 'react';
import algoliasearch from 'algoliasearch/lite';
import { InstantSearch, SearchBox, Hits } from 'react-instantsearch-dom';

const searchClient = algoliasearch('Y0X8ZCRBXG', '52c42c01e372d7bd64136651c56ffe94');

const Search = () => {
    return (
        <InstantSearch searchClient={searchClient} indexName="Messages">
            <SearchBox />
            <Hits />
            </InstantSearch>
        );
}

export default Search;