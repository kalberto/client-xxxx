const path = require('path')

const onComplete = (results) => {
    console.log(`${results.stringReplacers.find(replacer => replacer.slot == '__componentName__').slotValue.toUpperCase()} criado com sucesso!`);
}

const component = {
	option: 'Component',
	defaultCase: '(pascalCase)',
	entry: {
        folderPath: path.resolve(__dirname, 'templates/component/'),
    },
	stringReplacers: ['__componentName__'],
	output: {
		path: path.resolve(__dirname, '../../src/components/__componentName__(pascalCase)/'),
		pathAndFileNameDefaultCase: '(pascalCase)',
	},
	onComplete
};

const section = {
    option: 'Section',
    defaultCase: '(pascalCase)',
    entry: {
        folderPath: path.resolve(__dirname, 'templates/section/'),
    },
    stringReplacers: ['__componentName__', '__viewName__'],
    output: {
        path: path.resolve(__dirname, '../../src/sections/__viewName__(pascalCase)/__componentName__(pascalCase)'),
        pathAndFileNameDefaultCase: '(pascalCase)',
    },
    onComplete
}

const template = {
    option: 'Template',
    defaultCase: '(pascalCase)',
    entry: {
        folderPath: path.resolve(__dirname, 'templates/template/'),
    },
    stringReplacers: ['__componentName__'],
    output: {
        path: path.resolve(__dirname, '../../src/templates/__componentName__(pascalCase)/'),
        pathAndFileNameDefaultCase: '(pascalCase)',
    },
    onComplete
}

const view = {
    option: 'View',
    defaultCase: '(pascalCase)',
    entry: {
        folderPath: path.resolve(__dirname, 'templates/view/'),
    },
    stringReplacers: ['__componentName__'],
    output: {
        path: path.resolve(__dirname, '../../src/views/__componentName__(pascalCase)/'),
        pathAndFileNameDefaultCase: '(pascalCase)',
    },
    onComplete
}

module.exports = {
    component,
    section,
    template,
    view
}