import React, { useContext } from 'react';

const TextInput = props => {
    const { name, label, width, onChange } = props;

    return (
        <div className={`field width-${width}`}>
            <div className="heading">
                <label htmlFor={name}>
                    {label}
                </label>
            </div>
            <div className="input ltr">
                <input id={name} type="text" className={`text${width === '100' ? ' fullwidth' : ''}`} name={name} onChange={onChange} />
            </div>
        </div>
    );
}

export default TextInput;