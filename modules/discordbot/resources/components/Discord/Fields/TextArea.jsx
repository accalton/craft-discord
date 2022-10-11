import React, { useContext } from 'react';

const TextArea = props => {
    const { name, label, width, onChange } = props;

    return (
        <div className={`field width-${width}`}>
            <div className="heading">
                <label htmlFor={name}>
                    {label}
                </label>
            </div>
            <div className="input ltr">
                <textarea id={name} type="text" className="text fullwidth" name={name} onChange={onChange} />
            </div>
        </div>
    );
}

export default TextArea;