<?php
namespace T3voila\TvplusLinkvalidator;

use TYPO3\CMS\Backend\Utility\BackendUtility;
use TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools;
use TYPO3\CMS\Core\Html\HtmlParser;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Linkvalidator\LinkAnalyzer;

use Ppi\TemplaVoilaPlus\Utility\TemplaVoilaUtility;

/**
 * This class provides Processing Link Analyzing for TemplaVoilà! Plus flex form field.
 * Could be generalized for all flex form fields. But that should be done in core.
 */
class LinkAnalyzerSlot
{
    /** @var LinkAnalyzer */
    private $linkAnalyzer;

    /**
     * @param array $results Array of broken links
     * @param array $record Record to analyse
     * @param string $table Table name of the record
     * @param array $fields Array of fields to analyze
     * @return array
     */
    public function beforeAnalyzeRecordSlot(array $results, array $record, $table, array $fields, LinkAnalyzer $linkAnalyzer)
    {
        /** @var $htmlParser HtmlParser */
        $this->htmlParser = GeneralUtility::makeInstance(HtmlParser::class);

        $this->linkAnalyzer = $linkAnalyzer; // Needed to call the two public methods
        $this->results = $results; // Needed inside the flexFormCallBack

        // We only process tt_content
        // @TODO pages flexform could also have links
        if ($table === 'tt_content') {
            // Search if fields are in the mod.linkvalidator.searchFields list
            $keyFlex = array_search('tx_templavoilaplus_flex', $fields, true);
            $keyMap = array_search('tx_templavoilaplus_map', $fields, true);
            if ($keyFlex && $keyMap) {
                // unset this fields as we will process them here
                // So it doesn't get parsed later on again
                unset($fields[$keyFlex]);
                unset($fields[$keyMap]);
                // Now look if we have data to process here
                if (!empty($record['tx_templavoilaplus_flex']) && !empty($record['tx_templavoilaplus_map'])) {
                    // @TODO traverseFlexFormXMLData should put the pid inside $PA
                    $this->traversePid = $record['pid'];

                    // @TODO This seams not performant, as the FlexForm is loaded for every element
                    // But mostly elements should share DS
                    /** @var FlexFormTools */
                    $flexObj = GeneralUtility::makeInstance(FlexFormTools::class);
                    $flexObj->traverseFlexFormXMLData(
                        $table,
                        'tx_templavoilaplus_flex',
                        $record,
                        $this,
                        'checkField_flexFormCallBack'
                    );
                }
            }
        }

        return [
            $this->results,
            $record,
            $table,
            $fields,
            $linkAnalyzer
        ];
    }

    /**
     * Call back function for deleting file relations for flexform fields in records which are being completely deleted.
     *
     * @param array $dataStructure The DS for field to check
     * @param string $dataValue
     * @param array $PA
     * @param string $structurePath not used
     * @param object $parentObject not used
     * @return void
     */
    public function checkField_flexFormCallBack($dataStructure, $value, $PA, $structurePath, $parentObject)
    {
        $this->analyzeRecord(
            $dataStructure['TCEforms']['config'],
            $PA['table'],
            $structurePath,
            [
                'uid' => $PA['uid'],
                'pid' => $this->traversePid,
                $structurePath => $value,
            ]
        );
    }

    /**
     * Find all supported broken links for a specific flexform field.
     * Mostly taken from TYPO3 Cores LinkAnalyzer.
     *
     * @param array $results Array of broken links
     * @param string $table Table name of the record
     * @param array $fields Array of fields to analyze
     * @param array $record Record to analyse
     * @return void
     */
    protected function analyzeRecord($conf, $table, $field, $record)
    {
        $idRecord = $record['uid'];
        $valueField = $record[$field];
        // Check if a TCA configured field has soft references defined (see TYPO3 Core API document)
        if (!empty($conf['softref']) && (string)$valueField !== '') {
            // Explode the list of soft references/parameters
            $softRefs = BackendUtility::explodeSoftRefParserList($conf['softref']);
            if ($softRefs !== false) {
                // Traverse soft references
                foreach ($softRefs as $spKey => $spParams) {
                    /** @var $softRefObj \TYPO3\CMS\Core\Database\SoftReferenceIndex */
                    $softRefObj = BackendUtility::softRefParserObj($spKey);
                    // If there is an object returned...
                    if (is_object($softRefObj)) {
                        // Do processing
                        $resultArray = $softRefObj->findRef($table, $field, $idRecord, $valueField, $spKey, $spParams);
                        if (!empty($resultArray['elements'])) {
                            if ($spKey == 'typolink_tag') {
                                $this->linkAnalyzer->analyseTypoLinksPublic($resultArray, $this->results, $this->htmlParser, $record, $field, $table);
                            } else {
                                $this->linkAnalyzer->analyseLinksPublic($resultArray, $this->results, $record, $field, $table);
                            }
                        }
                    }
                }
            }
        }
    }
}
