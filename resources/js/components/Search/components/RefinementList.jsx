import React from 'react';
import { connectRefinementList } from 'react-instantsearch-dom';

const CustomRefinementList = ({ items, refine }) => {
    items = items.sort((a, b) => {
        const labelA = a.label.toUpperCase();
        const labelB = b.label.toUpperCase();

        if (labelA < labelB) {
            return -1;
        } else if (labelA > labelB) {
            return 1;
        } else {
            return 0;
        }
    });

    return (
        <div className="list">
            {items.map((item, index) => {
                let id = item.label.toLowerCase().replaceAll(" ", "-").replaceAll("/", "-");

                return (
                    <div className="item" key={index}>
                        <input
                            type="checkbox"
                            id={id}
                            onChange={() => {
                                refine(item.value)
                            }}
                            checked={item.isRefined ? "checked" : ""}
                        />
                        <label htmlFor={id}>
                            {item.label}
                        </label>
                    </div>
                );
            }
            )}
        </div>
    );
};

const RefinementList = connectRefinementList(CustomRefinementList);
export default RefinementList;