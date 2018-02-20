<?php
namespace Ppi\Templavoilaplus\Linkvalidator\Xclass;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * This class provides Processing plugin implementation
 */
class LinkAnalyzer extends \TYPO3\CMS\Linkvalidator\LinkAnalyzer
{

    /**
     * Find all supported broken links for a specific link list
     *
     * @param array $resultArray findRef parsed records
     * @param array $results Array of broken links
     * @param array $record UID of the current record
     * @param string $field The current field
     * @param string $table The current table
     * @return void
     */
    public function analyseLinksPublic(array $resultArray, array &$results, array $record, $field, $table)
    {
        parent::analyseLinks($resultArray, $results, $record, $field, $table);
    }


    /**
     * Find all supported broken links for a specific typoLink
     *
     * @param array $resultArray findRef parsed records
     * @param array $results Array of broken links
     * @param HtmlParser $htmlParser Instance of html parser
     * @param array $record The current record
     * @param string $field The current field
     * @param string $table The current table
     * @return void
     */
    public function analyseTypoLinksPublic(array $resultArray, array &$results, $htmlParser, array $record, $field, $table)
    {
        parent::analyseTypoLinks($resultArray, $results, $htmlParser, $record, $field, $table);
    }
}
