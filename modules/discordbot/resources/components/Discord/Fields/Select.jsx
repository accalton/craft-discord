import React from 'react';
import axios from 'axios';

const Select = props => {
    const { name, label, onChange, options, width } = props;

    return (
        <div className={`field width-${width}`}>
            <div className="heading">
                <label htmlFor={name}>
                    {label}
                </label>
            </div>
            <div className="input ltr">
                <div className="select">
                    <select name={name} onChange={onChange}>
                        <option value="">---</option>
                        {options.map(option => {
                            return <option key={option.id} value={option.id}>{option.name}</option>;
                        })}
                    </select>
                </div>
            </div>
        </div>
    );
}

export default Select;