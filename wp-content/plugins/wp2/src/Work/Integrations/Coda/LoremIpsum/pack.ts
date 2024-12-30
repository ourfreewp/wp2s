import * as coda from "@codahq/packs-sdk";

import { loremIpsum } from "lorem-ipsum";

export const pack = coda.newPack();

// count: 1,                // Number of "words", "sentences", or "paragraphs"
// format: "plain",         // "plain" or "html"
// paragraphLowerBound: 3,  // Min. number of sentences per paragraph.
// paragraphUpperBound: 7,  // Max. number of sentences per paragarph.
// random: Math.random,     // A PRNG function
// sentenceLowerBound: 5,   // Min. number of words per sentence.
// sentenceUpperBound: 15,  // Max. number of words per sentence.
// suffix: "\n",            // Line ending, defaults to "\n" or "\r\n" (win32)
// units: "sentences",      // paragraph(s), "sentence(s)", or "word(s)"
// words: ["ad", ...]       // Array of words to draw from

// CountParameter
const CountParameter = coda.makeParameter({
    type: coda.ParameterType.Number,
    name: "Count",
    description: "Number of words, sentences, or paragraphs",
    suggestedValue: 1,
    optional: true,
});

// FormatParameter
const FormatParameter = coda.makeParameter({
    type: coda.ParameterType.String,
    name: "Format",
    description: "Format of the text",
    suggestedValue: "plain",
    autocomplete: [
        { display: "Plain", value: "plain" },
        { display: "HTML", value: "html" },
    ],
    optional: true,
});

// ParagraphLowerBoundParameter
const ParagraphLowerBoundParameter = coda.makeParameter({
    type: coda.ParameterType.Number,
    name: "ParagraphLowerBound",
    suggestedValue: 3,
    description: "Minimum number of sentences per paragraph",
    optional: true,
});

// ParagraphUpperBoundParameter
const ParagraphUpperBoundParameter = coda.makeParameter({
    type: coda.ParameterType.Number,
    name: "ParagraphUpperBound",
    suggestedValue: 7,
    description: "Maximum number of sentences per paragraph",
    optional: true,
});

// SentenceLowerBoundParameter
const SentenceLowerBoundParameter = coda.makeParameter({
    type: coda.ParameterType.Number,
    name: "SentenceLowerBound",
    suggestedValue: 5,
    description: "Minimum number of words per sentence",
    optional: true,
});

// SentenceUpperBoundParameter
const SentenceUpperBoundParameter = coda.makeParameter({
    type: coda.ParameterType.Number,
    name: "SentenceUpperBound",
    suggestedValue: 15,
    description: "Maximum number of words per sentence",
    optional: true,
});

// SuffixParameter
const SuffixParameter = coda.makeParameter({
    type: coda.ParameterType.String,
    name: "Suffix",
    description: "Line ending",
    suggestedValue: "\n",
    autocomplete: [
        { display: "New Line", value: "\n" },
        { display: "Carriage Return", value: "\r\n" },
    ],
    optional: true,
});

// UnitsParameter
const UnitsParameter = coda.makeParameter({
    type: coda.ParameterType.String,
    name: "Units",
    description: "Paragraph(s), sentence(s), or word(s)",
    suggestedValue: "sentences",
    autocomplete: [
        { display: "Paragraphs", value: "paragraphs" },
        { display: "Sentences", value: "sentences" },
        { display: "Words", value: "words" },
    ],
    optional: true,
});

// WordsParameter

const WordsParameter = coda.makeParameter({
    type: coda.ParameterType.SparseStringArray,
    name: "Words",
    description: "Array of words to draw from",
    optional: true,
});

// Paragraph
pack.addFormula({
    name: "Paragraph",
    description: "Generate a paragraph of lorem ipsum text.",
    parameters: [
        WordsParameter,
    ],
    resultType: coda.ValueType.String,
    codaType: coda.ValueHintType.Html,
    cacheTtlSecs: 0,
    execute: async function ([Words], context) {


        const paragraph = loremIpsum({
            words: Words,
        });

        console.log(paragraph);

        return paragraph;
    },
});

// Paragraphs
pack.addFormula({
    name: "Paragraphs",
    description: "Generate paragraphs of lorem ipsum text.",
    parameters: [
        CountParameter,
        ParagraphLowerBoundParameter,
        ParagraphUpperBoundParameter,
        SentenceLowerBoundParameter,
        SentenceUpperBoundParameter,
        SuffixParameter,
        WordsParameter,
    ],
    resultType: coda.ValueType.String,
    codaType: coda.ValueHintType.Html,
    cacheTtlSecs: 0,
    execute: async function ([Count, ParagraphLowerBound, ParagraphUpperBound, SentenceLowerBound, SentenceUpperBound, Suffix, Words], context) {

        const paragraphs = loremIpsum({
            count: Count,
            format: 'html',
            paragraphLowerBound: ParagraphLowerBound,
            paragraphUpperBound: ParagraphUpperBound,
            sentenceLowerBound: SentenceLowerBound,
            sentenceUpperBound: SentenceUpperBound,
            suffix: "",
            units: "paragraphs",
            words: Words,
        });

        console.log(paragraphs);

        return paragraphs;
    },
});

// Sentence
pack.addFormula({
    name: "Sentence",
    description: "Generate a sentence of lorem ipsum text.",
    parameters: [
        CountParameter,
        SentenceLowerBoundParameter,
        SentenceUpperBoundParameter,
        SuffixParameter,
        WordsParameter,
    ],
    resultType: coda.ValueType.String,
    codaType: coda.ValueHintType.Html,
    cacheTtlSecs: 0,
    execute: async function ([Count, SentenceLowerBound, SentenceUpperBound, Suffix, Words], context) {

        const sentence = loremIpsum({
            count: Count,
            format: 'html',
            sentenceLowerBound: SentenceLowerBound,
            sentenceUpperBound: SentenceUpperBound,
            suffix: Suffix,
            units: "sentence",
            words: Words,
        });

        console.log(sentence);

        return sentence;
    },
});

// Sentences
pack.addFormula({
    name: "Sentences",
    description: "Generate sentences of lorem ipsum text.",
    parameters: [
        CountParameter,
        SentenceLowerBoundParameter,
        SentenceUpperBoundParameter,
        SuffixParameter,
        WordsParameter,
    ],
    resultType: coda.ValueType.String,
    codaType: coda.ValueHintType.Html,
    cacheTtlSecs: 0,
    execute: async function ([Count, SentenceLowerBound, SentenceUpperBound, Suffix, Words], context) {

        const sentences = loremIpsum({
            count: Count,
            format: 'html',
            sentenceLowerBound: SentenceLowerBound,
            sentenceUpperBound: SentenceUpperBound,
            suffix: Suffix,
            units: "sentences",
            words: Words,
        });

        console.log(sentences);

        return sentences;
    },
});

// Word
pack.addFormula({
    name: "Word",
    description: "Generate a word of lorem ipsum text.",
    parameters: [
        WordsParameter,
    ],
    resultType: coda.ValueType.String,
    codaType: coda.ValueHintType.Html,
    cacheTtlSecs: 0,
    execute: async function ([Words], context) {

        const word = loremIpsum({
            count: 1,
            format: 'html',
            units: "words",
            words: Words,
        });

        console.log(word);

        return word;
    },
});

// Words
pack.addFormula({
    name: "Words",
    description: "Generate words of lorem ipsum text.",
    parameters: [
        CountParameter,
        WordsParameter,
    ],
    resultType: coda.ValueType.String,
    codaType: coda.ValueHintType.Html,
    cacheTtlSecs: 0,
    execute: async function ([Count, Words], context) {

        const words = loremIpsum({
            count: Count,
            format: 'html',
            units: "words",
            words: Words,
        });

        console.log(words);

        return words;
    },
});
