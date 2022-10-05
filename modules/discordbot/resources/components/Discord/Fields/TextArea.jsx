import React from 'react';

const TextArea = props => {
    const { name, label, width } = props;

    return (
        <div className={`field width-${width}`}>
            <div className="heading">
                <label htmlFor={name}>
                    {label}
                </label>
            </div>
            <div className="input ltr">
                <textarea id={name} type="text" className="text fullwidth" name={name} />
            </div>
        </div>
    );
}

export default TextArea;